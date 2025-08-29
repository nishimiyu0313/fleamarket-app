<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\Condition;
use App\Models\User;
use App\Models\Payment;

class UsergetTest extends TestCase
{
    use RefreshDatabase;

    public function test_必要な情報が取得できる（プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧）()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        // プロフィール作成（画像含む）
        $user->profile()->create([
            'name' => 'テストユーザー',
            'postal_code' => '123-4567',
            'address' => '東京都新宿区テスト町1-1-1',
            'building' => 'テストビル101',
            'image' => 'profile.jpg',
        ]);

        // 出品商品を2件作成
        $condition = Condition::factory()->create();
        $itemsForSale = Item::factory()->count(2)->create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品名'
        ]);

        // 別ユーザー作成＆購入商品を2件作成
        $seller = User::factory()->create();
        $itemsPurchased = Item::factory()->count(2)->create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'is_sold' => true,
        ]);

        // 購入履歴作成
        foreach ($itemsPurchased as $item) {
            Payment::factory()->create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'status' => Payment::STATUS_COMPLETED,
            ]);
        }

        // 取得する画面へアクセス（例：ユーザープロフィールページ）
        $response = $this->actingAs($user)->get("/mypage/buy");

        $response->assertStatus(200);

        // プロフィール画像、ユーザー名の確認
        $response->assertSee('profile.jpg');
        $response->assertSee('テストユーザー');

        foreach ($itemsPurchased as $item) {
            $response->assertSee($item->name);
        }

        $sellResponse = $this->actingAs($user)->get("/mypage/sell");

        $sellResponse->assertStatus(200);
        $sellResponse->assertSee('テストユーザー');

        // 出品商品確認
        foreach ($itemsForSale as $item) {
            $sellResponse->assertSee($item->name);
        }

        
    }

   
}
