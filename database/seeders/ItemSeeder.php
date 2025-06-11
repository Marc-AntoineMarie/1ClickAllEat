<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Restaurant;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $restaurants = Restaurant::all();
        $categories = Category::all();

        $entreeCategory = $categories->where('name', 'Entrées')->first();
        $mainCategory = $categories->where('name', 'Plats principaux')->first();
        $dessertCategory = $categories->where('name', 'Desserts')->first();
        $drinkCategory = $categories->where('name', 'Boissons')->first();

        // Créer quelques plats pour chaque restaurant
        foreach ($restaurants as $index => $restaurant) {
            // Entrées
            $this->createItem([
                'name' => 'Entrée ' . ($index + 1),
                'description' => 'Délicieuse entrée du restaurant ' . $restaurant->name,
                'prix' => 6.50 + $index,
                'disponibility' => true,
                'restaurant_id' => $restaurant->id,
                'category_id' => $entreeCategory->id
            ]);

            // Plats principaux
            $this->createItem([
                'name' => 'Plat principal ' . ($index + 1),
                'description' => 'Spécialité du chef du restaurant ' . $restaurant->name,
                'prix' => 15.00 + $index * 2,
                'disponibility' => true,
                'restaurant_id' => $restaurant->id,
                'category_id' => $mainCategory->id
            ]);

            // Desserts
            $this->createItem([
                'name' => 'Dessert ' . ($index + 1),
                'description' => 'Dessert maison du restaurant ' . $restaurant->name,
                'prix' => 5.00 + $index,
                'disponibility' => true,
                'restaurant_id' => $restaurant->id,
                'category_id' => $dessertCategory->id
            ]);

            // Boissons
            $this->createItem([
                'name' => 'Boisson ' . ($index + 1),
                'description' => 'Boisson spéciale du restaurant ' . $restaurant->name,
                'prix' => 3.00 + $index * 0.5,
                'disponibility' => true,
                'restaurant_id' => $restaurant->id,
                'category_id' => $drinkCategory->id
            ]);
        }
    }

    /**
     * Créer un item avec les données fournies
     */
    private function createItem($data)
    {
        Item::create($data);
    }

    private function createItalianItems($restaurant, $categories)
    {
        // Entrées
        $entreeCategory = $categories->where('name', 'Entrées')->first();
        $items = [
            [
                'name' => 'Bruschetta',
                'description' => 'Pain grillé frotté à l\'ail et garni de tomates fraîches, basilic et huile d\'olive',
                'prix' => 6.50,
                'disponibility' => true,
            ],
            [
                'name' => 'Antipasti Misti',
                'description' => 'Assortiment de charcuteries et fromages italiens avec légumes grillés',
                'price' => 12.00,
                'image' => 'items/antipasti.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Carpaccio de Boeuf',
                'description' => 'Fines tranches de boeuf cru marinées avec huile d\'olive, parmesan et roquette',
                'price' => 9.50,
                'image' => 'items/carpaccio.jpg',
                'is_available' => true,
            ],
        ];

        foreach ($items as $itemData) {
            $item = new Item($itemData);
            $item->restaurant_id = $restaurant->id;
            $item->category_id = $entreeCategory->id;
            $item->save();
        }

        // Plats principaux
        $mainCategory = $categories->where('name', 'Plats principaux')->first();
        $items = [
            [
                'name' => 'Pizza Margherita',
                'description' => 'Sauce tomate, mozzarella et basilic frais',
                'price' => 10.00,
                'image' => 'items/margherita.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Lasagnes à la Bolognaise',
                'description' => 'Lasagnes maison avec sauce bolognaise, béchamel et parmesan',
                'price' => 13.50,
                'image' => 'items/lasagna.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Risotto aux Champignons',
                'description' => 'Risotto crémeux aux champignons et parmesan',
                'price' => 14.00,
                'image' => 'items/risotto.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Spaghetti Carbonara',
                'description' => 'Spaghetti avec sauce crémeuse, pancetta, oeuf et pecorino',
                'price' => 12.50,
                'image' => 'items/carbonara.jpg',
                'is_available' => true,
            ],
        ];

        foreach ($items as $itemData) {
            $item = new Item($itemData);
            $item->restaurant_id = $restaurant->id;
            $item->category_id = $mainCategory->id;
            $item->save();
        }

        // Desserts
        $dessertCategory = $categories->where('name', 'Desserts')->first();
        $items = [
            [
                'name' => 'Tiramisu',
                'description' => 'Dessert italien classique au mascarpone, café et cacao',
                'price' => 6.00,
                'image' => 'items/tiramisu.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Panna Cotta',
                'description' => 'Crème cuite italienne avec coulis de fruits rouges',
                'price' => 5.50,
                'image' => 'items/pannacotta.jpg',
                'is_available' => true,
            ],
        ];

        foreach ($items as $itemData) {
            $item = new Item($itemData);
            $item->restaurant_id = $restaurant->id;
            $item->category_id = $dessertCategory->id;
            $item->save();
        }

        // Boissons
        $drinkCategory = $categories->where('name', 'Boissons')->first();
        $items = [
            [
                'name' => 'Vin Rouge (Chianti)',
                'description' => 'Bouteille de Chianti, vin rouge italien',
                'price' => 18.00,
                'image' => 'items/wine.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Eau Minérale',
                'description' => 'Bouteille d\'eau minérale (plate ou gazeuse)',
                'price' => 3.00,
                'image' => 'items/water.jpg',
                'is_available' => true,
            ],
        ];

        foreach ($items as $itemData) {
            $item = new Item($itemData);
            $item->restaurant_id = $restaurant->id;
            $item->category_id = $drinkCategory->id;
            $item->save();
        }
    }

    private function createJapaneseItems($restaurant, $categories)
    {
        // Entrées
        $entreeCategory = $categories->where('name', 'Entrées')->first();
        $items = [
            [
                'name' => 'Edamame',
                'description' => 'Fèves de soja japonaises servies avec sel de mer',
                'price' => 4.50,
                'image' => 'items/edamame.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Gyoza',
                'description' => 'Raviolis japonais grillés au porc et légumes',
                'price' => 6.00,
                'image' => 'items/gyoza.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Miso Soup',
                'description' => 'Soupe traditionnelle japonaise au miso avec tofu et algues',
                'price' => 3.50,
                'image' => 'items/miso.jpg',
                'is_available' => true,
            ],
        ];

        foreach ($items as $itemData) {
            $item = new Item($itemData);
            $item->restaurant_id = $restaurant->id;
            $item->category_id = $entreeCategory->id;
            $item->save();
        }

        // Plats principaux
        $mainCategory = $categories->where('name', 'Plats principaux')->first();
        $items = [
            [
                'name' => 'Sushi Mix (12 pièces)',
                'description' => 'Assortiment de 12 pièces de sushi: nigiri et maki',
                'price' => 15.00,
                'image' => 'items/sushi.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Sashimi de Saumon',
                'description' => 'Tranches fines de saumon frais (8 pièces)',
                'price' => 12.50,
                'image' => 'items/sashimi.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Ramen au Porc',
                'description' => 'Nouilles ramen dans un bouillon riche avec porc, oeuf et légumes',
                'price' => 13.00,
                'image' => 'items/ramen.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Chirashi',
                'description' => 'Bol de riz vinaigré garni de poissons crus variés',
                'price' => 16.00,
                'image' => 'items/chirashi.jpg',
                'is_available' => true,
            ],
        ];

        foreach ($items as $itemData) {
            $item = new Item($itemData);
            $item->restaurant_id = $restaurant->id;
            $item->category_id = $mainCategory->id;
            $item->save();
        }

        // Desserts
        $dessertCategory = $categories->where('name', 'Desserts')->first();
        $items = [
            [
                'name' => 'Mochi',
                'description' => 'Gâteaux de riz japonais avec garniture sucrée',
                'price' => 5.00,
                'image' => 'items/mochi.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Dorayaki',
                'description' => 'Pâtisserie japonaise composée de deux pancakes fourrés à la pâte de haricot rouge',
                'price' => 4.50,
                'image' => 'items/dorayaki.jpg',
                'is_available' => true,
            ],
        ];

        foreach ($items as $itemData) {
            $item = new Item($itemData);
            $item->restaurant_id = $restaurant->id;
            $item->category_id = $dessertCategory->id;
            $item->save();
        }

        // Boissons
        $drinkCategory = $categories->where('name', 'Boissons')->first();
        $items = [
            [
                'name' => 'Thé Vert Japonais',
                'description' => 'Thé vert japonais traditionnel',
                'price' => 3.50,
                'image' => 'items/greentea.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Sake',
                'description' => 'Vin de riz japonais traditionnel',
                'price' => 8.00,
                'image' => 'items/sake.jpg',
                'is_available' => true,
            ],
        ];

        foreach ($items as $itemData) {
            $item = new Item($itemData);
            $item->restaurant_id = $restaurant->id;
            $item->category_id = $drinkCategory->id;
            $item->save();
        }
    }

    private function createFrenchItems($restaurant, $categories)
    {
        // Entrées
        $entreeCategory = $categories->where('name', 'Entrées')->first();
        $items = [
            [
                'name' => 'Soupe à l\'Oignon',
                'description' => 'Soupe à l\'oignon gratinée au fromage',
                'price' => 7.50,
                'image' => 'items/onionsoup.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Escargots de Bourgogne',
                'description' => 'Escargots au beurre d\'ail et persil (6 pièces)',
                'price' => 9.00,
                'image' => 'items/escargots.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Foie Gras',
                'description' => 'Foie gras maison avec confiture d\'oignons et pain toasté',
                'price' => 14.00,
                'image' => 'items/foiegras.jpg',
                'is_available' => true,
            ],
        ];

        foreach ($items as $itemData) {
            $item = new Item($itemData);
            $item->restaurant_id = $restaurant->id;
            $item->category_id = $entreeCategory->id;
            $item->save();
        }

        // Plats principaux
        $mainCategory = $categories->where('name', 'Plats principaux')->first();
        $items = [
            [
                'name' => 'Boeuf Bourguignon',
                'description' => 'Ragoût de boeuf mijoté au vin rouge avec légumes et lardons',
                'price' => 16.50,
                'image' => 'items/boeufbourguignon.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Coq au Vin',
                'description' => 'Poulet mijoté au vin rouge avec champignons et oignons',
                'price' => 15.00,
                'image' => 'items/coqauvin.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Steak Frites',
                'description' => 'Entrecôte grillée servie avec frites maison et sauce béarnaise',
                'price' => 18.00,
                'image' => 'items/steakfrites.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Quiche Lorraine',
                'description' => 'Tarte salée avec lardons, oignons et crème fraîche',
                'price' => 12.00,
                'image' => 'items/quiche.jpg',
                'is_available' => true,
            ],
        ];

        foreach ($items as $itemData) {
            $item = new Item($itemData);
            $item->restaurant_id = $restaurant->id;
            $item->category_id = $mainCategory->id;
            $item->save();
        }

        // Desserts
        $dessertCategory = $categories->where('name', 'Desserts')->first();
        $items = [
            [
                'name' => 'Crème Brûlée',
                'description' => 'Crème vanillée avec croûte caramélisée',
                'price' => 6.50,
                'image' => 'items/cremebrulee.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Tarte Tatin',
                'description' => 'Tarte aux pommes caramélisées servie tiède avec crème fraîche',
                'price' => 7.00,
                'image' => 'items/tartetatin.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Mousse au Chocolat',
                'description' => 'Mousse au chocolat noir légère et aérienne',
                'price' => 6.00,
                'image' => 'items/chocolatemousse.jpg',
                'is_available' => true,
            ],
        ];

        foreach ($items as $itemData) {
            $item = new Item($itemData);
            $item->restaurant_id = $restaurant->id;
            $item->category_id = $dessertCategory->id;
            $item->save();
        }

        // Boissons
        $drinkCategory = $categories->where('name', 'Boissons')->first();
        $items = [
            [
                'name' => 'Vin Rouge (Bordeaux)',
                'description' => 'Bouteille de vin rouge de Bordeaux',
                'price' => 22.00,
                'image' => 'items/redwine.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Vin Blanc (Chablis)',
                'description' => 'Bouteille de vin blanc de Chablis',
                'price' => 20.00,
                'image' => 'items/whitewine.jpg',
                'is_available' => true,
            ],
        ];

        foreach ($items as $itemData) {
            $item = new Item($itemData);
            $item->restaurant_id = $restaurant->id;
            $item->category_id = $drinkCategory->id;
            $item->save();
        }
    }

    private function createIndianItems($restaurant, $categories)
    {
        // Entrées
        $entreeCategory = $categories->where('name', 'Entrées')->first();
        $items = [
            [
                'name' => 'Samosas',
                'description' => 'Chaussons frits farcis de légumes épicés (2 pièces)',
                'price' => 5.50,
                'image' => 'items/samosas.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Pakoras',
                'description' => 'Beignets de légumes frits à la farine de pois chiches',
                'price' => 4.50,
                'image' => 'items/pakoras.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Naan au Fromage',
                'description' => 'Pain indien fourré au fromage cuit au tandoor',
                'price' => 3.50,
                'image' => 'items/cheesenant.jpg',
                'is_available' => true,
            ],
        ];

        foreach ($items as $itemData) {
            $item = new Item($itemData);
            $item->restaurant_id = $restaurant->id;
            $item->category_id = $entreeCategory->id;
            $item->save();
        }

        // Plats principaux
        $mainCategory = $categories->where('name', 'Plats principaux')->first();
        $items = [
            [
                'name' => 'Poulet Tikka Masala',
                'description' => 'Poulet mariné cuit au tandoor dans une sauce tomate et crème épicée',
                'price' => 14.50,
                'image' => 'items/tikkamasala.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Agneau Korma',
                'description' => 'Agneau mijoté dans une sauce crémeuse aux noix de cajou et épices douces',
                'price' => 16.00,
                'image' => 'items/korma.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Daal Makhani',
                'description' => 'Lentilles noires mijotées avec beurre, crème et épices',
                'price' => 10.00,
                'image' => 'items/daal.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Biryani au Poulet',
                'description' => 'Riz basmati parfumé cuit avec poulet, épices et herbes',
                'price' => 13.50,
                'image' => 'items/biryani.jpg',
                'is_available' => true,
            ],
        ];

        foreach ($items as $itemData) {
            $item = new Item($itemData);
            $item->restaurant_id = $restaurant->id;
            $item->category_id = $mainCategory->id;
            $item->save();
        }

        // Desserts
        $dessertCategory = $categories->where('name', 'Desserts')->first();
        $items = [
            [
                'name' => 'Gulab Jamun',
                'description' => 'Boulettes de lait frites et trempées dans un sirop parfumé à la cardamome',
                'price' => 4.50,
                'image' => 'items/gulabjamun.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Kulfi',
                'description' => 'Glace indienne traditionnelle à la pistache et cardamome',
                'price' => 5.00,
                'image' => 'items/kulfi.jpg',
                'is_available' => true,
            ],
        ];

        foreach ($items as $itemData) {
            $item = new Item($itemData);
            $item->restaurant_id = $restaurant->id;
            $item->category_id = $dessertCategory->id;
            $item->save();
        }

        // Boissons
        $drinkCategory = $categories->where('name', 'Boissons')->first();
        $items = [
            [
                'name' => 'Lassi Mangue',
                'description' => 'Boisson au yaourt et mangue',
                'price' => 4.00,
                'image' => 'items/lassi.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Chai',
                'description' => 'Thé indien aux épices et au lait',
                'price' => 3.00,
                'image' => 'items/chai.jpg',
                'is_available' => true,
            ],
        ];

        foreach ($items as $itemData) {
            $item = new Item($itemData);
            $item->restaurant_id = $restaurant->id;
            $item->category_id = $drinkCategory->id;
            $item->save();
        }
    }
}
