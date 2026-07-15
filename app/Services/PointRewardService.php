<?php

namespace App\Services;

use App\Models\PointReward;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PointRewardService
{
    /**
     * Add points to user
     */
    public function addPoints(User $user, int $points, string $description): void
    {
        DB::transaction(function () use ($user, $points, $description) {
            $previousBalance = $user->total_points;
            
            $user->increment('total_points', $points);

            $user->pointTransactions()->create([
                'points' => $points,
                'type' => 'credit',
                'description' => $description,
                'balance_after' => $user->total_points,
            ]);

            // Check for level upgrade
            $this->checkLevelUpgrade($user, $previousBalance);

            Log::info("Added {$points} points to user {$user->id}. New balance: {$user->total_points}");
        });
    }

    /**
     * Deduct points from user
     */
    public function deductPoints(User $user, int $points): void
    {
        if ($user->total_points < $points) {
            throw new \Exception('Insufficient points');
        }

        DB::transaction(function () use ($user, $points) {
            $user->decrement('total_points', $points);

            $user->pointTransactions()->create([
                'points' => $points,
                'type' => 'debit',
                'description' => 'Points redeemed',
                'balance_after' => $user->total_points,
            ]);

            Log::info("Deducted {$points} points from user {$user->id}. New balance: {$user->total_points}");
        });
    }

    /**
     * Get current point balance
     */
    public function getBalance(User $user): int
    {
        return $user->total_points;
    }

    /**
     * Process reward exchange
     */
    public function processExchange(User $user, PointReward $reward): void
    {
        if ($user->total_points < $reward->points_required) {
            throw new \Exception('Insufficient points for this reward');
        }

        DB::transaction(function () use ($user, $reward) {
            // Deduct points
            $this->deductPoints($user, $reward->points_required);

            // Create redemption record
            $user->rewardRedemptions()->create([
                'reward_id' => $reward->id,
                'points_used' => $reward->points_required,
                'status' => 'completed',
            ]);

            Log::info("User {$user->id} exchanged reward {$reward->id} for {$reward->points_required} points");
        });
    }

    /**
     * Check and process level upgrade
     */
    private function checkLevelUpgrade(User $user, int $previousBalance): void
    {
        $levels = [
            ['name' => 'Bronze', 'min_points' => 0],
            ['name' => 'Silver', 'min_points' => 1000],
            ['name' => 'Gold', 'min_points' => 5000],
            ['name' => 'Platinum', 'min_points' => 10000],
            ['name' => 'Diamond', 'min_points' => 25000],
        ];

        $newLevel = null;

        foreach ($levels as $level) {
            if ($user->total_points >= $level['min_points']) {
                $newLevel = $level;
            }
        }

        if ($newLevel && $user->level_id) {
            $currentLevel = $user->level;
            
            if ($currentLevel->name !== $newLevel['name']) {
                // Update user level
                $user->update(['level_id' => $this->getLevelIdByName($newLevel['name'])]);
                
                Log::info("User {$user->id} upgraded to {$newLevel['name']} level");
            }
        }
    }

    /**
     * Get level ID by name
     */
    private function getLevelIdByName(string $name): ?int
    {
        return \App\Models\Level::where('name', $name)->first()?->id;
    }

    /**
     * Get user point statistics
     */
    public function getPointStatistics(User $user): array
    {
        $transactions = $user->pointTransactions();
        
        $totalEarned = (clone $transactions)->where('type', 'credit')->sum('points');
        $totalSpent = (clone $transactions)->where('type', 'debit')->sum('points');
        
        return [
            'current_balance' => $user->total_points,
            'total_earned' => $totalEarned,
            'total_spent' => $totalSpent,
            'transaction_count' => $transactions->count(),
        ];
    }

    /**
     * Award points for order completion
     */
    public function awardOrderPoints(User $user, float $orderAmount): void
    {
        // Calculate points (1 point per Rp 1000 spent)
        $points = (int) ($orderAmount / 1000);

        if ($points > 0) {
            $this->addPoints($user, $points, "Points earned from order: Rp {$orderAmount}");
        }
    }

    /**
     * Award points for referral
     */
    public function awardReferralPoints(User $referrer, User $referee): void
    {
        $referralPoints = 100;

        $this->addPoints($referrer, $referralPoints, "Referral bonus for user: {$referee->username}");

        Log::info("Referral points awarded to user {$referrer->id} for referring {$referee->id}");
    }

    /**
     * Get available rewards for user
     */
    public function getAvailableRewards(User $user): \Illuminate\Database\Eloquent\Collection
    {
        return PointReward::where('is_active', true)
            ->where('points_required', '<=', $user->total_points)
            ->orderBy('points_required', 'asc')
            ->get();
    }

    /**
     * Get user redemption history
     */
    public function getRedemptionHistory(User $user, int $limit = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $user->rewardRedemptions()
            ->with('reward')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }
}
