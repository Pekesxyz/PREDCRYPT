<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CoinGeckoService
{
    protected string $baseUrl = 'https://api.coingecko.com/api/v3';

    /**
     * Daftar koin yang didukung
     */
    public static array $supportedCoins = [
        'bitcoin' => ['name' => 'Bitcoin', 'symbol' => 'BTC', 'image' => 'https://assets.coingecko.com/coins/images/1/small/bitcoin.png'],
        'ethereum' => ['name' => 'Ethereum', 'symbol' => 'ETH', 'image' => 'https://assets.coingecko.com/coins/images/279/small/ethereum.png'],
        'solana' => ['name' => 'Solana', 'symbol' => 'SOL', 'image' => 'https://assets.coingecko.com/coins/images/4128/small/solana.png'],
        'binancecoin' => ['name' => 'BNB', 'symbol' => 'BNB', 'image' => 'https://assets.coingecko.com/coins/images/825/small/bnb-icon2_2x.png'],
    ];

    /**
     * Ambil harga terkini semua koin yang didukung
     * Cache selama 5 menit
     */
    public function getCurrentPrices(): array
    {
        return Cache::remember('coingecko_markets_data', 60, function () {
            try {
                $ids = implode(',', array_keys(self::$supportedCoins));
                $response = Http::timeout(10)->get("{$this->baseUrl}/coins/markets", [
                    'vs_currency' => 'usd',
                    'ids' => $ids,
                    'sparkline' => 'true'
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $result = [];

                    foreach ($data as $coin) {
                        $id = $coin['id'];
                        if (isset(self::$supportedCoins[$id])) {
                            $result[] = [
                                'id' => $id,
                                'name' => self::$supportedCoins[$id]['name'],
                                'symbol' => self::$supportedCoins[$id]['symbol'],
                                'image' => $coin['image'] ?? self::$supportedCoins[$id]['image'],
                                'price' => $coin['current_price'] ?? 0,
                                'change' => $coin['price_change_percentage_24h'] ?? 0,
                                'marketCap' => $this->formatMarketCap($coin['market_cap'] ?? 0),
                                'sparkline' => $coin['sparkline_in_7d']['price'] ?? []
                            ];
                        }
                    }

                    // Sort berdasarkan urutan supportedCoins
                    usort($result, function($a, $b) {
                        $keys = array_keys(self::$supportedCoins);
                        return array_search($a['id'], $keys) - array_search($b['id'], $keys);
                    });

                    return $result;
                }
            } catch (\Exception $e) {
                \Log::warning('CoinGecko API error: ' . $e->getMessage());
            }

            // Fallback data jika API gagal
            return $this->getFallbackPrices();
        });
    }

    /**
     * Ambil data historis harga koin (30 hari)
     * Cache selama 10 menit
     */
    public function getHistoricalPrices(string $coinId, int $days = 30): array
    {
        return Cache::remember("coingecko_history_{$coinId}_{$days}", 600, function () use ($coinId, $days) {
            try {
                $response = Http::timeout(10)->get("{$this->baseUrl}/coins/{$coinId}/market_chart", [
                    'vs_currency' => 'usd',
                    'days' => $days,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $prices = $data['prices'] ?? [];

                    return array_map(function ($item) {
                        return [
                            'timestamp' => $item[0],
                            'price' => $item[1],
                        ];
                    }, $prices);
                }
            } catch (\Exception $e) {
                \Log::warning("CoinGecko history error for {$coinId}: " . $e->getMessage());
            }

            // Fallback: generate dummy historical data
            return $this->generateFallbackHistory($coinId, $days);
        });
    }

    /**
     * Ambil harga terkini satu koin
     */
    public function getCoinPrice(string $coinId): float
    {
        $prices = $this->getCurrentPrices();
        foreach ($prices as $coin) {
            if ($coin['id'] === $coinId) {
                return $coin['price'];
            }
        }
        return 0;
    }

    /**
     * Format market cap ke string yang lebih readable
     */
    private function formatMarketCap(float $value): string
    {
        if ($value >= 1e12) return number_format($value / 1e12, 2) . 'T';
        if ($value >= 1e9) return number_format($value / 1e9, 2) . 'B';
        if ($value >= 1e6) return number_format($value / 1e6, 2) . 'M';
        return number_format($value, 0);
    }

    /**
     * Data fallback jika API tidak tersedia
     */
    private function getFallbackPrices(): array
    {
        return [
            ['id' => 'bitcoin', 'name' => 'Bitcoin', 'symbol' => 'BTC', 'image' => self::$supportedCoins['bitcoin']['image'], 'price' => 67234.50, 'change' => 2.45, 'marketCap' => '1.32T'],
            ['id' => 'ethereum', 'name' => 'Ethereum', 'symbol' => 'ETH', 'image' => self::$supportedCoins['ethereum']['image'], 'price' => 3456.78, 'change' => -1.23, 'marketCap' => '415.6B'],
            ['id' => 'solana', 'name' => 'Solana', 'symbol' => 'SOL', 'image' => self::$supportedCoins['solana']['image'], 'price' => 148.52, 'change' => 3.21, 'marketCap' => '68.4B'],
            ['id' => 'binancecoin', 'name' => 'BNB', 'symbol' => 'BNB', 'image' => self::$supportedCoins['binancecoin']['image'], 'price' => 567.89, 'change' => 0.89, 'marketCap' => '87.2B'],
        ];
    }

    /**
     * Generate fallback historical data
     */
    private function generateFallbackHistory(string $coinId, int $days): array
    {
        $basePrices = [
            'bitcoin' => 67000, 'ethereum' => 3400,
            'solana' => 148, 'binancecoin' => 560,
        ];
        $base = $basePrices[$coinId] ?? 100;
        $result = [];
        $now = time() * 1000;

        for ($i = $days; $i >= 0; $i--) {
            $variation = $base * (0.95 + (mt_rand(0, 100) / 1000));
            $result[] = [
                'timestamp' => $now - ($i * 86400000),
                'price' => round($variation, 8),
            ];
        }

        return $result;
    }
}
