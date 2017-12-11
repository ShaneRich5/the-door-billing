<?php

use Illuminate\Database\Seeder;
use App\Models\MenuItem;
use App\Models\Category;
use App\Models\Tag;

class MenuSeeder extends Seeder
{
	/**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $meat_tag = Tag::create(['name' => 'meat']);
        $seafood_tag = Tag::create(['name' => 'seafood']);

        $chicken = Category::create(['name' => 'Chicken', 'catering_limit' => 1]);
        $red_meat = Category::create(['name' => 'Red Meat', 'catering_limit' => 1]);
        $fish = Category::create(['name' => 'Fish', 'catering_limit' => 1]);
        $general = Category::create(['name' => 'General', 'catering_limit' => 0]);
        $vegetables = Category::create(['name' => 'Vegetables', 'catering_limit' => 1]);
        $side = Category::create(['name' => 'Side', 'catering_limit' => 1]);
        $beverages = Category::create(['name' => 'Beverage', 'catering_limit' => 1]);

        $chicken_names = ['Jerk Chicken', 'Brown Stewed Chicken', 'Escovitched Chicken', 'Baked Chicken', 'Barbeque Chicken'];
        $red_meat_names = ['Oxtail', 'Curried Goat', 'Peppered Steak'];
        $fish_names = ['Brown Stewed Snapper', 'Escovitched Whitening', ' Grilled Salmon'];
        $general_names = ['Rice & Peas', 'Macaroni & Cheese', 'Plantains'];
        $vegetable_names = ['Garden Salad', 'Steamed Vegetable'];
        $side_names = ['Assortd Fruits', 'Platted Pastry'];
        $beverage_names = ['Lemonade', 'Soda', 'Water'];

        $this->createMenuItems($chicken_names, $chicken, $meat_tag);
        $this->createMenuItems($red_meat_names, $red_meat, $meat_tag);
        $this->createMenuItems($fish_names, $fish, $seafood_tag);
        $this->createMenuItems($general_names, $general);
        $this->createMenuItems($vegetable_names, $vegetables);
        $this->createMenuItems($side_names, $side);
        $this->createMenuItems($beverage_names, $beverages);
    }

    private function createMenuItems($names, $category, $tag = null) {
        foreach($names as $name) {
            $menuItem = MenuItem::create(['name' => $name, 'category_id' => $category->id]);
            if ($tag) {
                $menuItem->tags()->attach($menuItem);
            }
        }
    }
}