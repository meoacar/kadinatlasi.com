<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CalculatorController extends Controller
{
    public function bmi(Request $request)
    {
        $request->validate([
            'weight' => 'required|numeric|min:1|max:500',
            'height' => 'required|numeric|min:50|max:250',
            'age' => 'integer|min:15|max:100',
            'activity_level' => 'in:sedentary,light,moderate,active,very_active',
        ]);

        $weight = $request->weight;
        $height = $request->height / 100; // cm to m
        $bmi = $weight / ($height * $height);

        // İdeal kilo aralığı (BMI 18.5-24.9)
        $idealWeightMin = 18.5 * ($height * $height);
        $idealWeightMax = 24.9 * ($height * $height);
        
        $category = '';
        $advice = '';
        $healthRisk = '';
        $weightToLose = 0;
        $weightToGain = 0;

        if ($bmi < 18.5) {
            $category = 'Zayıf';
            $healthRisk = 'Düşük';
            $weightToGain = $idealWeightMin - $weight;
            $advice = 'Sağlıklı kilo almak için protein açısından zengin beslenme ve kuvvet antrenmanları önerilir.';
        } elseif ($bmi < 25) {
            $category = 'Normal';
            $healthRisk = 'Minimal';
            $advice = 'Mükemmel! Sağlıklı kilonuzu korumak için dengeli beslenme ve düzenli egzersiz yapın.';
        } elseif ($bmi < 30) {
            $category = 'Fazla Kilolu';
            $healthRisk = 'Artmış';
            $weightToLose = $weight - $idealWeightMax;
            $advice = 'Kalori açığı oluşturun: günlük 300-500 kalori azaltın ve haftada 150 dakika orta şiddetli egzersiz yapın.';
        } elseif ($bmi < 35) {
            $category = 'Obez (Sınıf I)';
            $healthRisk = 'Yüksek';
            $weightToLose = $weight - $idealWeightMax;
            $advice = 'Doktor kontrolünde kilo verme programı başlatın. Beslenme uzmanı desteği alın.';
        } elseif ($bmi < 40) {
            $category = 'Obez (Sınıf II)';
            $healthRisk = 'Çok Yüksek';
            $weightToLose = $weight - $idealWeightMax;
            $advice = 'Acil tıbbi müdahale gerekli. Endokrinolog ve beslenme uzmanına başvurun.';
        } else {
            $category = 'Obez (Sınıf III)';
            $healthRisk = 'Ekstrem Yüksek';
            $weightToLose = $weight - $idealWeightMax;
            $advice = 'Morbid obezite. Bariatrik cerrahi seçenekleri için doktorunuzla görüşün.';
        }

        // Kalori hesaplaması (eğer yaş ve aktivite seviyesi verilmişse)
        $dailyCalories = null;
        if ($request->age && $request->activity_level) {
            $bmr = (10 * $weight) + (6.25 * $request->height) - (5 * $request->age) - 161;
            $activityMultipliers = [
                'sedentary' => 1.2,
                'light' => 1.375,
                'moderate' => 1.55,
                'active' => 1.725,
                'very_active' => 1.9,
            ];
            $dailyCalories = round($bmr * $activityMultipliers[$request->activity_level]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'bmi' => round($bmi, 1),
                'category' => $category,
                'health_risk' => $healthRisk,
                'advice' => $advice,
                'weight' => $weight,
                'height' => $request->height,
                'ideal_weight_min' => round($idealWeightMin, 1),
                'ideal_weight_max' => round($idealWeightMax, 1),
                'weight_to_lose' => $weightToLose > 0 ? round($weightToLose, 1) : 0,
                'weight_to_gain' => $weightToGain > 0 ? round($weightToGain, 1) : 0,
                'daily_calories' => $dailyCalories,
            ]
        ]);
    }

    public function menstrualCycle(Request $request)
    {
        $request->validate([
            'last_period_date' => 'required|date|before_or_equal:today',
            'cycle_length' => 'integer|min:21|max:35',
        ]);

        $lastPeriod = Carbon::parse($request->last_period_date);
        $cycleLength = $request->cycle_length ?? 28;

        $nextPeriod = $lastPeriod->copy()->addDays($cycleLength);
        $ovulationDate = $lastPeriod->copy()->addDays($cycleLength - 14);
        $fertilePeriodStart = $ovulationDate->copy()->subDays(5);
        $fertilePeriodEnd = $ovulationDate->copy()->addDays(1);

        return response()->json([
            'success' => true,
            'data' => [
                'last_period_date' => $lastPeriod->format('Y-m-d'),
                'next_period_date' => $nextPeriod->format('Y-m-d'),
                'ovulation_date' => $ovulationDate->format('Y-m-d'),
                'fertile_period_start' => $fertilePeriodStart->format('Y-m-d'),
                'fertile_period_end' => $fertilePeriodEnd->format('Y-m-d'),
                'cycle_length' => $cycleLength,
                'days_until_next_period' => $nextPeriod->diffInDays(Carbon::now()),
            ]
        ]);
    }

    public function pregnancy(Request $request)
    {
        $request->validate([
            'last_period_date' => 'required|date|before_or_equal:today',
        ]);

        $lastPeriod = Carbon::parse($request->last_period_date);
        $today = Carbon::now();
        
        $daysSinceLastPeriod = $lastPeriod->diffInDays($today);
        $weeksSinceLastPeriod = floor($daysSinceLastPeriod / 7);
        $daysSinceWeek = $daysSinceLastPeriod % 7;

        $dueDate = $lastPeriod->copy()->addDays(280); // 40 weeks
        $daysUntilDue = $today->diffInDays($dueDate);

        return response()->json([
            'success' => true,
            'data' => [
                'last_period_date' => $lastPeriod->format('Y-m-d'),
                'weeks_pregnant' => $weeksSinceLastPeriod,
                'days_pregnant' => $daysSinceWeek,
                'due_date' => $dueDate->format('Y-m-d'),
                'days_until_due' => $daysUntilDue,
                'trimester' => $weeksSinceLastPeriod <= 12 ? 1 : ($weeksSinceLastPeriod <= 26 ? 2 : 3),
            ]
        ]);
    }

    public function calorie(Request $request)
    {
        $request->validate([
            'age' => 'required|integer|min:15|max:100',
            'weight' => 'required|numeric|min:30|max:200',
            'height' => 'required|numeric|min:140|max:220',
            'activity_level' => 'required|in:sedentary,light,moderate,active,very_active',
            'goal' => 'required|in:lose,maintain,gain',
        ]);

        // Mifflin-St Jeor Equation for women
        $bmr = (10 * $request->weight) + (6.25 * $request->height) - (5 * $request->age) - 161;

        $activityMultipliers = [
            'sedentary' => 1.2,
            'light' => 1.375,
            'moderate' => 1.55,
            'active' => 1.725,
            'very_active' => 1.9,
        ];

        $tdee = $bmr * $activityMultipliers[$request->activity_level];

        $goalCalories = $tdee;
        if ($request->goal === 'lose') {
            $goalCalories = $tdee - 500; // 0.5kg per week
        } elseif ($request->goal === 'gain') {
            $goalCalories = $tdee + 500; // 0.5kg per week
        }

        return response()->json([
            'success' => true,
            'data' => [
                'bmr' => round($bmr),
                'tdee' => round($tdee),
                'goal_calories' => round($goalCalories),
                'goal' => $request->goal,
                'activity_level' => $request->activity_level,
            ]
        ]);
    }

    public function waterIntake(Request $request)
    {
        $request->validate([
            'weight' => 'required|numeric|min:30|max:200',
            'activity_level' => 'required|in:low,moderate,high',
            'climate' => 'required|in:normal,hot,cold',
        ]);

        // Base calculation: 35ml per kg
        $baseWater = $request->weight * 35;

        // Activity adjustment
        $activityAdjustment = [
            'low' => 0,
            'moderate' => 500,
            'high' => 1000,
        ];

        // Climate adjustment
        $climateAdjustment = [
            'normal' => 0,
            'hot' => 500,
            'cold' => -200,
        ];

        $totalWater = $baseWater + 
                     $activityAdjustment[$request->activity_level] + 
                     $climateAdjustment[$request->climate];

        $glasses = round($totalWater / 250); // 250ml per glass

        return response()->json([
            'success' => true,
            'data' => [
                'daily_water_ml' => round($totalWater),
                'daily_water_liters' => round($totalWater / 1000, 1),
                'glasses_per_day' => $glasses,
                'weight' => $request->weight,
            ]
        ]);
    }

    public function financialPlanner(Request $request)
    {
        $request->validate([
            'monthly_income' => 'required|numeric|min:0',
            'monthly_expenses' => 'required|numeric|min:0',
            'savings_goal' => 'required|numeric|min:0',
            'goal_months' => 'required|integer|min:1|max:120',
            'current_savings' => 'numeric|min:0',
            'debt_amount' => 'numeric|min:0',
            'debt_interest_rate' => 'numeric|min:0|max:100',
        ]);

        $monthlyIncome = $request->monthly_income;
        $monthlyExpenses = $request->monthly_expenses;
        $savingsGoal = $request->savings_goal;
        $goalMonths = $request->goal_months;
        $currentSavings = $request->current_savings ?? 0;
        $debtAmount = $request->debt_amount ?? 0;
        $debtInterestRate = $request->debt_interest_rate ?? 0;

        $monthlySavings = $monthlyIncome - $monthlyExpenses;
        $remainingGoal = $savingsGoal - $currentSavings;
        $requiredMonthlySavings = $remainingGoal / $goalMonths;
        $canReachGoal = $monthlySavings >= $requiredMonthlySavings;
        $actualMonthsToGoal = $monthlySavings > 0 ? ceil($remainingGoal / $monthlySavings) : null;

        // Budget recommendations (50/30/20 rule)
        $recommendedNeeds = $monthlyIncome * 0.5;
        $recommendedWants = $monthlyIncome * 0.3;
        $recommendedSavings = $monthlyIncome * 0.2;

        // Debt analysis
        $monthlyDebtPayment = 0;
        $debtPayoffMonths = 0;
        if ($debtAmount > 0 && $debtInterestRate > 0) {
            $monthlyInterestRate = ($debtInterestRate / 100) / 12;
            $monthlyDebtPayment = ($debtAmount * $monthlyInterestRate * pow(1 + $monthlyInterestRate, 36)) / (pow(1 + $monthlyInterestRate, 36) - 1);
            $debtPayoffMonths = 36; // 3 yıl varsayımı
        }

        // Emergency fund calculation (6 months expenses)
        $emergencyFund = $monthlyExpenses * 6;
        $emergencyFundMonths = $monthlySavings > 0 ? ceil($emergencyFund / $monthlySavings) : null;

        // Investment recommendations
        $investmentRecommendations = [];
        if ($monthlySavings > 1000) {
            $investmentRecommendations[] = 'Aylık ' . number_format($monthlySavings * 0.3, 0) . ' TL yatırım fonu';
            $investmentRecommendations[] = 'Aylık ' . number_format($monthlySavings * 0.2, 0) . ' TL altın/döviz';
        }

        $advice = '';
        if ($monthlySavings <= 0) {
            $advice = 'Harcamalarınız gelirinizi aşıyor. Acil bütçe planı yapın ve gereksiz harcamaları kesin.';
        } elseif ($debtAmount > 0) {
            $advice = 'Önce borçlarınızı kapatın. Aylık ' . number_format($monthlyDebtPayment, 0) . ' TL borç ödemesi yapmanız önerilir.';
        } elseif ($currentSavings < $emergencyFund) {
            $advice = 'Önce acil durum fonu oluşturun. ' . number_format($emergencyFund, 0) . ' TL acil fon hedeflemelisiniz.';
        } elseif (!$canReachGoal) {
            $advice = 'Hedefinize ulaşmak için aylık ' . number_format($requiredMonthlySavings - $monthlySavings, 0) . ' TL daha tasarruf edin.';
        } else {
            $advice = 'Mükemmel! Hedeflerinize ulaşabilirsiniz. Yatırım seçeneklerini değerlendirin.';
        }

        return response()->json([
            'success' => true,
            'data' => [
                'monthly_income' => $monthlyIncome,
                'monthly_expenses' => $monthlyExpenses,
                'monthly_savings' => $monthlySavings,
                'savings_goal' => $savingsGoal,
                'current_savings' => $currentSavings,
                'remaining_goal' => $remainingGoal,
                'goal_months' => $goalMonths,
                'required_monthly_savings' => round($requiredMonthlySavings, 2),
                'can_reach_goal' => $canReachGoal,
                'actual_months_to_goal' => $actualMonthsToGoal,
                'recommended_needs' => round($recommendedNeeds, 2),
                'recommended_wants' => round($recommendedWants, 2),
                'recommended_savings' => round($recommendedSavings, 2),
                'emergency_fund' => round($emergencyFund, 2),
                'emergency_fund_months' => $emergencyFundMonths,
                'debt_amount' => $debtAmount,
                'monthly_debt_payment' => round($monthlyDebtPayment, 2),
                'debt_payoff_months' => $debtPayoffMonths,
                'investment_recommendations' => $investmentRecommendations,
                'advice' => $advice,
                'savings_rate_percentage' => $monthlyIncome > 0 ? round(($monthlySavings / $monthlyIncome) * 100, 1) : 0,
            ]
        ]);
    }

    public function fertilityTracker(Request $request)
    {
        $request->validate([
            'age' => 'required|integer|min:18|max:50',
            'cycle_length' => 'required|integer|min:21|max:35',
            'last_period_date' => 'required|date|before_or_equal:today',
            'trying_to_conceive' => 'boolean',
            'contraception_method' => 'in:none,pill,iud,condom,natural',
        ]);

        $age = $request->age;
        $cycleLength = $request->cycle_length;
        $lastPeriod = Carbon::parse($request->last_period_date);
        $tryingToConceive = $request->trying_to_conceive ?? false;
        $contraceptionMethod = $request->contraception_method ?? 'none';

        // Ovulation and fertile window calculations
        $ovulationDate = $lastPeriod->copy()->addDays($cycleLength - 14);
        $fertilePeriodStart = $ovulationDate->copy()->subDays(5);
        $fertilePeriodEnd = $ovulationDate->copy()->addDays(1);
        $nextPeriod = $lastPeriod->copy()->addDays($cycleLength);

        // Age-based fertility insights
        $fertilityRate = 100;
        $ageAdvice = '';
        if ($age < 25) {
            $fertilityRate = 95;
            $ageAdvice = 'En yüksek doğurganlık döneminde bulunuyorsunuz.';
        } elseif ($age < 30) {
            $fertilityRate = 90;
            $ageAdvice = 'Doğurganlık oranınız hala çok yüksek.';
        } elseif ($age < 35) {
            $fertilityRate = 80;
            $ageAdvice = 'Doğurganlık oranınız iyi seviyede.';
        } elseif ($age < 40) {
            $fertilityRate = 60;
            $ageAdvice = 'Doğurganlık oranınız azalmaya başladı. Doktor kontrolü önerilir.';
        } else {
            $fertilityRate = 30;
            $ageAdvice = 'Doğurganlık oranınız düşük. Uzman desteği alın.';
        }

        // Cycle regularity assessment
        $cycleRegularity = 'Normal';
        if ($cycleLength < 24 || $cycleLength > 32) {
            $cycleRegularity = 'Düzensiz';
        }

        // Conception probability this cycle
        $conceptionProbability = 0;
        if ($tryingToConceive && $contraceptionMethod === 'none') {
            $baseRate = $fertilityRate / 100;
            $cycleAdjustment = $cycleRegularity === 'Normal' ? 1.0 : 0.7;
            $conceptionProbability = round($baseRate * $cycleAdjustment * 20, 1); // 20% base monthly rate
        }

        // Recommendations
        $recommendations = [];
        if ($tryingToConceive) {
            $recommendations[] = 'Folik asit takviyesi alın (günlük 400mcg)';
            $recommendations[] = 'Sigara ve alkol tüketimini bırakın';
            $recommendations[] = 'Sağlıklı beslenin ve ideal kilonuzu koruyun';
            $recommendations[] = 'Stresi azaltın ve düzenli uyuyun';
            if ($age > 35) {
                $recommendations[] = '6 ayda hamile kalamadıysanız doktora başvurun';
            } else {
                $recommendations[] = '1 yılda hamile kalamadıysanız doktora başvurun';
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'age' => $age,
                'cycle_length' => $cycleLength,
                'cycle_regularity' => $cycleRegularity,
                'last_period_date' => $lastPeriod->format('Y-m-d'),
                'next_period_date' => $nextPeriod->format('Y-m-d'),
                'ovulation_date' => $ovulationDate->format('Y-m-d'),
                'fertile_period_start' => $fertilePeriodStart->format('Y-m-d'),
                'fertile_period_end' => $fertilePeriodEnd->format('Y-m-d'),
                'fertility_rate' => $fertilityRate,
                'conception_probability' => $conceptionProbability,
                'age_advice' => $ageAdvice,
                'trying_to_conceive' => $tryingToConceive,
                'contraception_method' => $contraceptionMethod,
                'recommendations' => $recommendations,
                'days_until_ovulation' => Carbon::now()->diffInDays($ovulationDate, false),
                'days_until_next_period' => Carbon::now()->diffInDays($nextPeriod, false),
            ]
        ]);
    }

    public function idealWeightCalculator(Request $request)
    {
        $request->validate([
            'height' => 'required|numeric|min:140|max:220',
            'age' => 'required|integer|min:18|max:80',
            'body_type' => 'in:small,medium,large',
        ]);

        $height = $request->height;
        $age = $request->age;
        $bodyType = $request->body_type ?? 'medium';

        // Robinson Formula (1983)
        $robinsonWeight = 49 + (1.7 * (($height - 152.4) / 2.54));
        
        // Miller Formula (1983)
        $millerWeight = 53.1 + (1.36 * (($height - 152.4) / 2.54));
        
        // Devine Formula (1974)
        $devineWeight = 45.5 + (2.3 * (($height - 152.4) / 2.54));
        
        // Hamwi Formula (1964)
        $hamwiWeight = 45.5 + (2.2 * (($height - 152.4) / 2.54));

        // Body type adjustments
        $adjustments = [
            'small' => 0.9,
            'medium' => 1.0,
            'large' => 1.1,
        ];
        
        $adjustment = $adjustments[$bodyType];
        
        $idealWeights = [
            'robinson' => round($robinsonWeight * $adjustment, 1),
            'miller' => round($millerWeight * $adjustment, 1),
            'devine' => round($devineWeight * $adjustment, 1),
            'hamwi' => round($hamwiWeight * $adjustment, 1),
        ];
        
        $averageIdealWeight = round(array_sum($idealWeights) / count($idealWeights), 1);
        
        // Healthy weight range (BMI 18.5-24.9)
        $heightM = $height / 100;
        $minHealthyWeight = round(18.5 * ($heightM * $heightM), 1);
        $maxHealthyWeight = round(24.9 * ($heightM * $heightM), 1);

        return response()->json([
            'success' => true,
            'data' => [
                'height' => $height,
                'age' => $age,
                'body_type' => $bodyType,
                'ideal_weights' => $idealWeights,
                'average_ideal_weight' => $averageIdealWeight,
                'healthy_weight_range' => [
                    'min' => $minHealthyWeight,
                    'max' => $maxHealthyWeight,
                ],
                'recommendations' => [
                    'Bu hesaplamalar genel rehberlik içindir',
                    'Kas kütlesi ve vücut yapısı dikkate alınmalıdır',
                    'Doktor kontrolünde kilo hedefi belirleyin',
                    'Sağlıklı beslenme ve egzersiz önemlidir',
                ]
            ]
        ]);
    }

    public function ovulationCalculator(Request $request)
    {
        $request->validate([
            'last_period_date' => 'required|date|before_or_equal:today',
            'cycle_length' => 'integer|min:21|max:35',
            'luteal_phase_length' => 'integer|min:10|max:16',
        ]);

        $lastPeriod = Carbon::parse($request->last_period_date);
        $cycleLength = $request->cycle_length ?? 28;
        $lutealPhaseLength = $request->luteal_phase_length ?? 14;
        
        // Calculate ovulation date
        $ovulationDate = $lastPeriod->copy()->addDays($cycleLength - $lutealPhaseLength);
        
        // Fertile window (5 days before + ovulation day + 1 day after)
        $fertilePeriodStart = $ovulationDate->copy()->subDays(5);
        $fertilePeriodEnd = $ovulationDate->copy()->addDays(1);
        
        // Next period
        $nextPeriod = $lastPeriod->copy()->addDays($cycleLength);
        
        // Peak fertility days (2 days before ovulation)
        $peakFertilityStart = $ovulationDate->copy()->subDays(2);
        $peakFertilityEnd = $ovulationDate->copy();
        
        // Days calculations
        $today = Carbon::now();
        $daysUntilOvulation = $today->diffInDays($ovulationDate, false);
        $daysUntilNextPeriod = $today->diffInDays($nextPeriod, false);
        
        // Current cycle phase
        $currentPhase = '';
        $phaseDescription = '';
        
        if ($today->between($lastPeriod, $lastPeriod->copy()->addDays(5))) {
            $currentPhase = 'Menstrual';
            $phaseDescription = 'Regl dönemi - Dinlenme ve kendine iyi bakma zamanı';
        } elseif ($today->between($lastPeriod->copy()->addDays(6), $fertilePeriodStart->copy()->subDay())) {
            $currentPhase = 'Follicular';
            $phaseDescription = 'Foliküler faz - Enerji artışı ve yeni başlangıçlar için ideal';
        } elseif ($today->between($fertilePeriodStart, $fertilePeriodEnd)) {
            $currentPhase = 'Fertile';
            $phaseDescription = 'Doğurgan dönem - En yüksek hamile kalma şansı';
        } else {
            $currentPhase = 'Luteal';
            $phaseDescription = 'Luteal faz - Vücut hamileliğe hazırlanıyor';
        }

        return response()->json([
            'success' => true,
            'data' => [
                'last_period_date' => $lastPeriod->format('Y-m-d'),
                'cycle_length' => $cycleLength,
                'luteal_phase_length' => $lutealPhaseLength,
                'ovulation_date' => $ovulationDate->format('Y-m-d'),
                'fertile_period_start' => $fertilePeriodStart->format('Y-m-d'),
                'fertile_period_end' => $fertilePeriodEnd->format('Y-m-d'),
                'peak_fertility_start' => $peakFertilityStart->format('Y-m-d'),
                'peak_fertility_end' => $peakFertilityEnd->format('Y-m-d'),
                'next_period_date' => $nextPeriod->format('Y-m-d'),
                'days_until_ovulation' => $daysUntilOvulation,
                'days_until_next_period' => $daysUntilNextPeriod,
                'current_phase' => $currentPhase,
                'phase_description' => $phaseDescription,
                'tips' => [
                    'Bazal vücut sıcaklığınızı takip edin',
                    'Servikal mukus değişikliklerini gözlemleyin',
                    'Ovulasyon test kitleri kullanabilirsiniz',
                    'Düzenli uyku ve beslenme önemlidir',
                ]
            ]
        ]);
    }

    public function dueDateCalculator(Request $request)
    {
        $request->validate([
            'last_period_date' => 'required|date|before_or_equal:today',
            'cycle_length' => 'integer|min:21|max:35',
        ]);

        $lastPeriod = Carbon::parse($request->last_period_date);
        $cycleLength = $request->cycle_length ?? 28;
        
        // Naegele's Rule: LMP + 280 days
        $dueDateNaegele = $lastPeriod->copy()->addDays(280);
        
        // Adjusted for cycle length
        $ovulationAdjustment = $cycleLength - 28;
        $dueDateAdjusted = $dueDateNaegele->copy()->addDays($ovulationAdjustment);
        
        // Current pregnancy info
        $today = Carbon::now();
        $daysSinceLastPeriod = $lastPeriod->diffInDays($today);
        $weeksSinceLastPeriod = floor($daysSinceLastPeriod / 7);
        $daysSinceWeek = $daysSinceLastPeriod % 7;
        
        // Trimester calculation
        $trimester = 1;
        if ($weeksSinceLastPeriod >= 13 && $weeksSinceLastPeriod < 27) {
            $trimester = 2;
        } elseif ($weeksSinceLastPeriod >= 27) {
            $trimester = 3;
        }
        
        // Days until due date
        $daysUntilDue = $today->diffInDays($dueDateAdjusted, false);
        $weeksUntilDue = floor($daysUntilDue / 7);
        
        // Milestones
        $milestones = [
            ['week' => 12, 'milestone' => 'İlk trimester sonu - Düşük riski azalır'],
            ['week' => 20, 'milestone' => 'Anatomik ultrason zamanı'],
            ['week' => 24, 'milestone' => 'Yaşayabilirlik sınırı'],
            ['week' => 28, 'milestone' => 'Üçüncü trimester başlangıcı'],
            ['week' => 37, 'milestone' => 'Miadında doğum başlangıcı'],
            ['week' => 40, 'milestone' => 'Tahmini doğum tarihi'],
        ];
        
        $nextMilestone = null;
        foreach ($milestones as $milestone) {
            if ($weeksSinceLastPeriod < $milestone['week']) {
                $nextMilestone = $milestone;
                break;
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'last_period_date' => $lastPeriod->format('Y-m-d'),
                'due_date_naegele' => $dueDateNaegele->format('Y-m-d'),
                'due_date_adjusted' => $dueDateAdjusted->format('Y-m-d'),
                'weeks_pregnant' => $weeksSinceLastPeriod,
                'days_pregnant' => $daysSinceWeek,
                'trimester' => $trimester,
                'days_until_due' => $daysUntilDue,
                'weeks_until_due' => $weeksUntilDue,
                'next_milestone' => $nextMilestone,
                'pregnancy_tips' => [
                    'Düzenli doktor kontrollerine gidin',
                    'Folik asit ve prenatal vitamin alın',
                    'Sağlıklı beslenin ve bol su için',
                    'Hafif egzersiz yapın (doktor onayı ile)',
                    'Stres yönetimi ve yeterli uyku önemli',
                ]
            ]
        ]);
    }

    public function bodyFatCalculator(Request $request)
    {
        $request->validate([
            'age' => 'required|integer|min:18|max:80',
            'weight' => 'required|numeric|min:30|max:200',
            'height' => 'required|numeric|min:140|max:220',
            'neck' => 'required|numeric|min:20|max:50',
            'waist' => 'required|numeric|min:50|max:150',
            'hip' => 'required|numeric|min:70|max:180',
        ]);

        $age = $request->age;
        $weight = $request->weight;
        $height = $request->height;
        $neck = $request->neck;
        $waist = $request->waist;
        $hip = $request->hip;
        
        // US Navy Method for women
        $bodyFatPercentage = 495 / (1.29579 - 0.35004 * log10($waist + $hip - $neck) + 0.22100 * log10($height)) - 450;
        $bodyFatPercentage = max(0, min(50, $bodyFatPercentage)); // Clamp between 0-50%
        
        // Body fat categories for women
        $category = '';
        $description = '';
        $healthRisk = '';
        
        if ($bodyFatPercentage < 10) {
            $category = 'Çok Düşük';
            $description = 'Esansiyel yağ seviyesinin altında';
            $healthRisk = 'Yüksek';
        } elseif ($bodyFatPercentage < 16) {
            $category = 'Sporcu';
            $description = 'Atletik vücut yapısı';
            $healthRisk = 'Düşük';
        } elseif ($bodyFatPercentage < 20) {
            $category = 'Fitness';
            $description = 'Fit ve sağlıklı seviye';
            $healthRisk = 'Düşük';
        } elseif ($bodyFatPercentage < 25) {
            $category = 'Ortalama';
            $description = 'Kabul edilebilir seviye';
            $healthRisk = 'Düşük';
        } elseif ($bodyFatPercentage < 32) {
            $category = 'Fazla';
            $description = 'Ortalama üstü yağ oranı';
            $healthRisk = 'Orta';
        } else {
            $category = 'Obez';
            $description = 'Yüksek yağ oranı';
            $healthRisk = 'Yüksek';
        }
        
        // Calculate lean body mass
        $bodyFatMass = ($bodyFatPercentage / 100) * $weight;
        $leanBodyMass = $weight - $bodyFatMass;
        
        // Recommendations
        $recommendations = [];
        if ($bodyFatPercentage < 16) {
            $recommendations[] = 'Yağ oranınız çok düşük, sağlık açısından riskli olabilir';
            $recommendations[] = 'Beslenme uzmanına danışın';
        } elseif ($bodyFatPercentage > 32) {
            $recommendations[] = 'Kardiyovasküler egzersizleri artırın';
            $recommendations[] = 'Kalori açığı oluşturun';
            $recommendations[] = 'Kuvvet antrenmanı ekleyin';
        } else {
            $recommendations[] = 'Mevcut seviyenizi korumak için düzenli egzersiz yapın';
            $recommendations[] = 'Dengeli beslenme programı uygulayın';
        }

        return response()->json([
            'success' => true,
            'data' => [
                'body_fat_percentage' => round($bodyFatPercentage, 1),
                'category' => $category,
                'description' => $description,
                'health_risk' => $healthRisk,
                'body_fat_mass' => round($bodyFatMass, 1),
                'lean_body_mass' => round($leanBodyMass, 1),
                'measurements' => [
                    'weight' => $weight,
                    'height' => $height,
                    'neck' => $neck,
                    'waist' => $waist,
                    'hip' => $hip,
                ],
                'recommendations' => $recommendations,
            ]
        ]);
    }
}