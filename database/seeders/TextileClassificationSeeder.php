<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\TextileCategory;
use App\Models\TextileSubcategory;
use App\Models\TextileProduct;
use App\Models\CategoryAttributeRule;
use App\Models\FibreMaster;
use App\Models\YarnMaster;
use App\Models\FabricMaster;
use App\Models\ProcessMaster;

class TextileClassificationSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────────────────────────────────────
        // 1. MAIN CATEGORIES (CAT-01 to CAT-06)
        // ─────────────────────────────────────────────────────────────
        $categoriesData = [
            [
                'code' => 'CAT-01',
                'name' => 'Fibres',
                'slug' => 'fibres',
                'icon' => 'fas fa-seedling',
                'description' => 'Natural, synthetic, man-made, recycled and specialty textile raw fibres.',
                'sort_order' => 1,
            ],
            [
                'code' => 'CAT-02',
                'name' => 'Yarns',
                'slug' => 'yarns',
                'icon' => 'fas fa-scroll',
                'description' => 'Spun, filament, textured, fancy, composite and industrial specialty yarns.',
                'sort_order' => 2,
            ],
            [
                'code' => 'CAT-03',
                'name' => 'Fabrics',
                'slug' => 'fabrics',
                'icon' => 'fas fa-layer-group',
                'description' => 'Woven, knitted, non-woven, coated, traditional handloom and specialty fabrics.',
                'sort_order' => 3,
            ],
            [
                'code' => 'CAT-04',
                'name' => 'Garments',
                'slug' => 'garments',
                'icon' => 'fas fa-shirt',
                'description' => 'Men\'s, women\'s, children\'s, traditional Indian apparel, sportswear and workwear.',
                'sort_order' => 4,
            ],
            [
                'code' => 'CAT-05',
                'name' => 'Home Textiles & Made-ups',
                'slug' => 'home-textiles-made-ups',
                'icon' => 'fas fa-couch',
                'description' => 'Household linen, bed sheets, curtains, towels, rugs, cushions and textile bags.',
                'sort_order' => 5,
            ],
            [
                'code' => 'CAT-06',
                'name' => 'Technical & Industrial Textiles',
                'slug' => 'technical-industrial-textiles',
                'icon' => 'fas fa-industry',
                'description' => 'Industrial, medical, automotive, hygiene, filtration and geotextile products.',
                'sort_order' => 6,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[$c['code']] = TextileCategory::updateOrCreate(
                ['code' => $c['code']],
                $c
            );
        }

        // ─────────────────────────────────────────────────────────────
        // 2. CATEGORY ATTRIBUTE RULES
        // ─────────────────────────────────────────────────────────────
        $rules = [
            // CAT-01: Fibres -> Fibre: required, Yarn: hidden, Fabric: hidden, Process: hidden
            ['category_code' => 'CAT-01', 'attribute_type' => 'fibre', 'requirement_level' => 'required', 'is_multiple' => true, 'help_text' => 'Specify raw fibre variety and composition percentage.'],
            ['category_code' => 'CAT-01', 'attribute_type' => 'yarn', 'requirement_level' => 'hidden', 'is_multiple' => false, 'help_text' => null],
            ['category_code' => 'CAT-01', 'attribute_type' => 'fabric', 'requirement_level' => 'hidden', 'is_multiple' => false, 'help_text' => null],
            ['category_code' => 'CAT-01', 'attribute_type' => 'process', 'requirement_level' => 'hidden', 'is_multiple' => false, 'help_text' => null],

            // CAT-02: Yarns -> Fibre: required, Yarn: required, Fabric: hidden, Process: optional
            ['category_code' => 'CAT-02', 'attribute_type' => 'fibre', 'requirement_level' => 'required', 'is_multiple' => true, 'help_text' => 'Fibre content blended in the yarn.'],
            ['category_code' => 'CAT-02', 'attribute_type' => 'yarn', 'requirement_level' => 'required', 'is_multiple' => true, 'help_text' => 'Spinning and structural yarn type (e.g. Ring-Spun, DTY).'],
            ['category_code' => 'CAT-02', 'attribute_type' => 'fabric', 'requirement_level' => 'hidden', 'is_multiple' => false, 'help_text' => null],
            ['category_code' => 'CAT-02', 'attribute_type' => 'process', 'requirement_level' => 'optional', 'is_multiple' => true, 'help_text' => 'Yarn dyeing, texturing or functional treatments.'],

            // CAT-03: Fabrics -> Fibre: relevant, Yarn: optional, Fabric: required, Process: optional
            ['category_code' => 'CAT-03', 'attribute_type' => 'fibre', 'requirement_level' => 'relevant', 'is_multiple' => true, 'help_text' => 'Fibre composition (e.g. 100% Cotton, 80/20 Cotton/Polyester).'],
            ['category_code' => 'CAT-03', 'attribute_type' => 'yarn', 'requirement_level' => 'optional', 'is_multiple' => true, 'help_text' => 'Yarn counts or filament structure used in warp/weft.'],
            ['category_code' => 'CAT-03', 'attribute_type' => 'fabric', 'requirement_level' => 'required', 'is_multiple' => true, 'help_text' => 'Weave or knit construction (e.g. Poplin, Twill, Single Jersey).'],
            ['category_code' => 'CAT-03', 'attribute_type' => 'process', 'requirement_level' => 'optional', 'is_multiple' => true, 'help_text' => 'Finishing, printing, dyeing or coating applied.'],

            // CAT-04: Garments -> Fibre: relevant, Yarn: optional, Fabric: relevant, Process: optional
            ['category_code' => 'CAT-04', 'attribute_type' => 'fibre', 'requirement_level' => 'relevant', 'is_multiple' => true, 'help_text' => 'Fibre composition of garment fabric.'],
            ['category_code' => 'CAT-04', 'attribute_type' => 'yarn', 'requirement_level' => 'optional', 'is_multiple' => true, 'help_text' => 'Yarn details if known or premium (e.g. Combed Cotton).'],
            ['category_code' => 'CAT-04', 'attribute_type' => 'fabric', 'requirement_level' => 'relevant', 'is_multiple' => true, 'help_text' => 'Fabric construction used for garment shell/lining.'],
            ['category_code' => 'CAT-04', 'attribute_type' => 'process', 'requirement_level' => 'optional', 'is_multiple' => true, 'help_text' => 'Dyeing, wash finishes, prints or garment embellishments.'],

            // CAT-05: Home Textiles -> Fibre: relevant, Yarn: optional, Fabric: relevant, Process: optional
            ['category_code' => 'CAT-05', 'attribute_type' => 'fibre', 'requirement_level' => 'relevant', 'is_multiple' => true, 'help_text' => 'Fibre content (e.g. 100% Cotton, Linen blend).'],
            ['category_code' => 'CAT-05', 'attribute_type' => 'yarn', 'requirement_level' => 'optional', 'is_multiple' => true, 'help_text' => 'Yarn structure.'],
            ['category_code' => 'CAT-05', 'attribute_type' => 'fabric', 'requirement_level' => 'relevant', 'is_multiple' => true, 'help_text' => 'Fabric type (e.g. Percale, Terry, Jacquard).'],
            ['category_code' => 'CAT-05', 'attribute_type' => 'process', 'requirement_level' => 'optional', 'is_multiple' => true, 'help_text' => 'Printing, antimicrobial, pre-shrunk or stain-resistant finish.'],

            // CAT-06: Technical Textiles -> Fibre: relevant, Yarn: optional, Fabric: relevant, Process: optional
            ['category_code' => 'CAT-06', 'attribute_type' => 'fibre', 'requirement_level' => 'relevant', 'is_multiple' => true, 'help_text' => 'High-performance or synthetic fibre specifications.'],
            ['category_code' => 'CAT-06', 'attribute_type' => 'yarn', 'requirement_level' => 'optional', 'is_multiple' => true, 'help_text' => 'High-tenacity or industrial yarn type.'],
            ['category_code' => 'CAT-06', 'attribute_type' => 'fabric', 'requirement_level' => 'relevant', 'is_multiple' => true, 'help_text' => 'Non-woven, mesh, membrane or ballistic fabric structure.'],
            ['category_code' => 'CAT-06', 'attribute_type' => 'process', 'requirement_level' => 'optional', 'is_multiple' => true, 'help_text' => 'Protective coating, flame-retardant or filtration finish.'],
        ];

        foreach ($rules as $r) {
            $cat = $categories[$r['category_code']];
            CategoryAttributeRule::updateOrCreate(
                ['category_id' => $cat->id, 'attribute_type' => $r['attribute_type']],
                [
                    'requirement_level' => $r['requirement_level'],
                    'is_multiple' => $r['is_multiple'],
                    'help_text' => $r['help_text'],
                ]
            );
        }

        // ─────────────────────────────────────────────────────────────
        // 3. SUBCATEGORIES & PRODUCTS
        // ─────────────────────────────────────────────────────────────
        $hierarchy = [
            'CAT-01' => [
                'Natural Plant Fibres' => [
                    'Cotton', 'Flax', 'Linen', 'Hemp', 'Jute', 'Ramie', 'Kenaf', 'Sunn',
                    'Nettle', 'Sisal', 'Pineapple', 'Coir', 'Coconut', 'Coconut husk',
                    'Banana', 'Bamboo', 'Okra'
                ],
                'Natural Animal Fibres' => [
                    'Sheep wool', 'Merino wool', 'Australian wool', 'Cashmere', 'Mohair',
                    'Mulberry silk', 'Tussar silk', 'Eri silk', 'Muga silk', 'Pashmina'
                ],
                'Regenerated Cellulose Fibres' => [
                    'Viscose rayon', 'Viscose staple', 'Viscose filament', 'Lyocell',
                    'Modal', 'Polynosic rayon', 'Cuprammonium rayon'
                ],
                'Acetate & Related Fibres' => [
                    'Acetate', 'Cellulose acetate', 'Acetate filament', 'Acetate staple', 'Triacetate'
                ],
                'Synthetic Fibres' => [
                    'Polyester (PET)', 'Nylon', 'Nylon 6', 'Nylon 66', 'Acrylic', 'Modacrylic',
                    'Elastane', 'Spandex', 'Polypropylene', 'Polyethylene'
                ],
                'Recycled Fibres' => [
                    'Recycled cotton', 'Recycled wool', 'Recycled polyester', 'Recycled PET',
                    'Recycled nylon', 'Recycled acrylic', 'Recycled polypropylene',
                    'Recycled cellulose', 'Recycled viscose', 'Recycled lyocell', 'Recycled elastane'
                ],
                'High-performance & Specialty Fibres' => [
                    'Aramid variants', 'Kevlar', 'Nomex', 'PTFE', 'Polyvinyl alcohol', 'PVC',
                    'CPVC', 'Glass fibre', 'E-glass', 'S-glass', 'Carbon fibre', 'Ceramic fibre', 'UHMWPE'
                ],
            ],

            'CAT-02' => [
                'Spun Yarns' => [
                    'Spun Yarn', 'Ring-Spun Yarn', 'Compact-Spun Yarn', 'Open-End Yarn',
                    'Rotor-Spun Yarn', 'Vortex-Spun Yarn', 'Carded Yarn', 'Combed Yarn'
                ],
                'Filament Yarns' => [
                    'Filament Yarn', 'Monofilament Yarn', 'Multifilament Yarn', 'POY',
                    'FDY', 'DTY', 'ATY', 'HTY'
                ],
                'Ply & Twisted Yarns' => [
                    'Single Yarn', 'Ply Yarn', 'Folded Yarn', 'Cabled Yarn', 'Cord Yarn',
                    'Double-Ply Yarn', 'Three-Ply Yarn'
                ],
                'Textured & Fancy Yarns' => [
                    'Textured Yarn', 'Slub Yarn', 'Nep Yarn', 'Chenille Yarn', 'Chainette Yarn',
                    'Feather Yarn', 'Eyelash Yarn', 'Metallic Yarn', 'Melange Yarn', 'Space-Dyed Yarn'
                ],
                'Covered & Composite Yarns' => [
                    'Core-Spun Yarn', 'Core Yarn', 'Covered Yarn', 'Single-Covered Yarn',
                    'Double-Covered Yarn', 'Composite Yarn', 'Spandex Core Yarn'
                ],
                'Specialty & Functional Yarns' => [
                    'High-Tenacity Yarn', 'Flame-Resistant Yarn', 'Heat-Resistant Yarn',
                    'Chemical-Resistant Yarn', 'Antistatic Yarn', 'Conductive Yarn', 'Antibacterial Yarn'
                ],
                'Industrial & Technical Yarns' => [
                    'Industrial Yarn', 'Filter Yarn', 'Geotextile Yarn', 'Tire-Cord Yarn',
                    'Reinforcement Yarn', 'Medical Yarn'
                ],
                'End-use Yarns' => [
                    'Sewing Thread/Yarn', 'Embroidery Yarn', 'Knitting Yarn', 'Weaving Yarn',
                    'Hosiery Yarn', 'Carpet Yarn', 'Rug Yarn', 'Braiding Yarn'
                ],
            ],

            'CAT-03' => [
                'Woven Fabrics' => [
                    'Plain Weave', 'Tabby', 'Poplin', 'Voile', 'Muslin', 'Cambric', 'Lawn',
                    'Percale', 'Twill', 'Herringbone', 'Denim', 'Satin', 'Sateen', 'Taffeta',
                    'Oxford', 'Canvas', 'Chiffon', 'Georgette', 'Organza', 'Jacquard Fabric', 'Dobby Fabric'
                ],
                'Knitted Fabrics' => [
                    'Single jersey', 'Double jersey', 'Rib knit', 'Interlock', 'Piqué knit',
                    'Ponte knit', 'French terry', 'Raschel knit', 'Fleece fabric', 'Tricot'
                ],
                'Pile & Terry Fabrics' => [
                    'Velvet', 'Velveteen', 'Corduroy', 'Plush', 'Terry towel fabric',
                    'Terry velour', 'Fleece pile fabric', 'Warp-pile fabric'
                ],
                'Lace & Net Fabrics' => [
                    'Chantilly lace', 'Guipure lace', 'Bobbin lace', 'Crochet lace',
                    'Tulle', 'Net', 'Raschel lace'
                ],
                'Non-woven Fabrics' => [
                    'Felt', 'Needle-punched fabric', 'Spunbonded', 'Meltblown', 'Wet-laid', 'Air-laid'
                ],
                'Mesh Fabrics' => [
                    'Mesh', 'Power mesh', 'Spacer mesh', 'Raschel mesh', 'Sports mesh',
                    'Technical mesh', '3D mesh'
                ],
                'Coated & Laminated Fabrics' => [
                    'PVC-coated fabric', 'PU-coated fabric', 'Acrylic-coated fabric',
                    'Silicone-coated fabric', 'Rubber-coated fabric', 'Laminated fabric', 'Foam-laminated fabric'
                ],
                'Technical & Industrial Fabrics' => [
                    'Filter fabric', 'Conveyor-belt fabric', 'Tire-cord fabric', 'Geotextile fabric',
                    'Industrial fabric', 'Reinforcement fabric', 'Protective fabric', 'Ballistic fabric'
                ],
                'Traditional & Regional Fabrics' => [
                    'Khadi', 'Chanderi', 'Maheshwari', 'Kota Doria', 'Banarasi Brocade',
                    'Jamdani', 'Ikat', 'Patola', 'Paithani', 'Kalamkari fabric'
                ],
                'Specialty & Finished Fabrics' => [
                    'Quilted fabric', 'Embossed fabric', 'Burnout fabric', 'Washed fabric',
                    'Stone-washed fabric', 'Mercerized fabric', 'Sanforized fabric'
                ],
            ],

            'CAT-04' => [
                'Men\'s / Boys\' Upper Garments' => [
                    'Shirts', 'Dress shirts', 'Casual shirts', 'Formal shirts', 'Polo shirts',
                    'T-shirts', 'Vests', 'Singlets', 'Tank tops', 'Undershirts', 'Sweatshirts',
                    'Hoodies', 'Pullovers', 'Sweaters', 'Cardigans', 'Jerseys', 'Waistcoats',
                    'Blazers', 'Jackets', 'Coats', 'Overcoats', 'Parkas', 'Anoraks', 'Windbreakers',
                    'Raincoats', 'Overalls', 'Coveralls', 'Workwear'
                ],
                'Men\'s / Boys\' Lower Garments' => [
                    'Trousers', 'Pants', 'Formal trousers', 'Casual trousers', 'Jeans',
                    'Chinos', 'Shorts', 'Bermuda shorts', 'Cargo pants', 'Track pants',
                    'Joggers', 'Leggings', 'Bib-and-brace overalls', 'Dungarees', 'Breeches'
                ],
                'Women\'s / Girls\' Upper Garments' => [
                    'Blouses', 'Shirts', 'Tops', 'T-shirts', 'Tank tops', 'Camisoles',
                    'Tunics', 'Kurtis', 'Kurtas', 'Sweaters', 'Cardigans', 'Pullovers',
                    'Sweatshirts', 'Hoodies', 'Shrugs', 'Boleros', 'Waistcoats', 'Vests',
                    'Jackets', 'Blazers', 'Coats', 'Capes', 'Ponchos', 'Parkas', 'Raincoats'
                ],
                'Women\'s / Girls\' Lower Garments' => [
                    'Trousers', 'Pants', 'Jeans', 'Shorts', 'Skirts', 'Mini skirts',
                    'Midi skirts', 'Maxi skirts', 'Culottes', 'Leggings', 'Jeggings',
                    'Palazzos', 'Track pants', 'Joggers', 'Salwar', 'Churidar', 'Patiala', 'Dhoti pants'
                ],
                'Dresses & One-Piece Garments' => [
                    'Dresses', 'Day dresses', 'Evening dresses', 'Party dresses', 'Maxi dresses',
                    'Mini dresses', 'Midi dresses', 'Shift dresses', 'Shirt dresses', 'Wrap dresses',
                    'Sundresses', 'Gowns', 'Formal gowns', 'Jumpsuits', 'Playsuits', 'Rompers'
                ],
                'Indian / Traditional Garments' => [
                    'Sarees', 'Salwar suits', 'Salwar kameez', 'Churidar suits', 'Anarkali suits',
                    'Lehenga', 'Lehenga choli', 'Ghagra', 'Ghagra choli', 'Kurta', 'Kurti',
                    'Sherwani', 'Achkan', 'Nehru jacket', 'Dhoti', 'Lungi', 'Mundu',
                    'Angavastram', 'Panchakacham', 'Veshti', 'Dupatta', 'Chunni', 'Stole',
                    'Shawl', 'Pheran', 'Mekhela chador', 'Phanek'
                ],
                'Undergarments' => [
                    'Briefs', 'Boxer shorts', 'Boxers', 'Trunks', 'Undershirts', 'Vests',
                    'Brassieres', 'Bras', 'Panties', 'Slips', 'Petticoats', 'Camisoles',
                    'Underwear sets', 'Thermal underwear', 'Shapewear', 'Bodysuits'
                ],
                'Sleepwear' => [
                    'Nightwear', 'Nightshirts', 'Nightdresses', 'Pyjamas', 'Pyjama sets',
                    'Sleep shirts', 'Sleep shorts', 'Robes', 'Dressing gowns', 'Bathrobes'
                ],
                'Sportswear' => [
                    'Sports T-shirts', 'Sports tops', 'Sports bras', 'Sports shorts',
                    'Track pants', 'Tracksuits', 'Training suits', 'Running wear', 'Cycling wear',
                    'Cycling shorts', 'Football jerseys', 'Sports jerseys', 'Athletic wear',
                    'Gym wear', 'Yoga wear', 'Swimwear', 'Swimsuits', 'Swimming trunks', 'Rash guards'
                ],
                'Scarves & Neckwear' => [
                    'Scarves', 'Stoles', 'Mufflers', 'Neck warmers', 'Shawls', 'Wraps',
                    'Bandanas', 'Hijabs', 'Veils'
                ],
                'Workwear & Protective Clothing' => [
                    'Work shirts', 'Work trousers', 'Work jackets', 'Coveralls', 'Overalls',
                    'Bib overalls', 'Boiler suits', 'Industrial clothing', 'High-visibility clothing',
                    'Reflective clothing', 'Flame-resistant clothing', 'Heat-resistant clothing',
                    'Chemical-resistant clothing', 'Waterproof protective clothing',
                    'Disposable protective garments', 'Medical gowns', 'Surgical gowns', 'Laboratory coats', 'Aprons'
                ],
                'Baby & Children\'s Garments' => [
                    'Baby bodysuits', 'Baby vests', 'Baby rompers', 'Baby dresses', 'Baby shirts',
                    'Baby trousers', 'Baby shorts', 'Baby sleepwear', 'Baby jackets',
                    'Children\'s T-shirts', 'Children\'s shirts', 'Children\'s dresses',
                    'Children\'s trousers', 'Children\'s skirts', 'Children\'s outerwear', 'Children\'s underwear'
                ],
                'Hosiery & Legwear' => [
                    'Socks', 'Ankle socks', 'Crew socks', 'Knee socks', 'Stockings',
                    'Pantyhose', 'Tights', 'Leg warmers', 'Compression stockings', 'Compression socks'
                ],
                'Headwear' => [
                    'Hats', 'Caps', 'Sun hats', 'Bucket hats', 'Beanies', 'Berets',
                    'Headbands', 'Turbans', 'Scarves/head coverings', 'Traditional headwear'
                ],
                'Gloves & Handwear' => [
                    'Gloves', 'Mittens', 'Fingerless gloves', 'Industrial gloves', 'Sports gloves',
                    'Thermal gloves', 'Protective textile gloves'
                ],
            ],

            'CAT-05' => [
                'Household Linen' => [
                    'Bed linen', 'Bed sheets', 'Flat sheets', 'Fitted sheets', 'Pillowcases',
                    'Bolster covers', 'Duvet covers', 'Quilt covers', 'Bedspreads', 'Bed covers',
                    'Blankets', 'Comforters', 'Quilts', 'Eiderdowns', 'Mattress protectors',
                    'Mattress covers', 'Sleeping bags'
                ],
                'Curtains & Window Textiles' => [
                    'Curtains', 'Drapes', 'Window curtains', 'Door curtains', 'Shower curtains',
                    'Curtain panels', 'Curtain liners', 'Blinds made from textile materials', 'Valances', 'Window shades'
                ],
                'Towels & Terry Products' => [
                    'Bath towels', 'Hand towels', 'Face towels', 'Guest towels', 'Beach towels',
                    'Sports towels', 'Kitchen towels', 'Tea towels', 'Dish towels', 'Terry towels',
                    'Bath sheets', 'Washcloths', 'Bath mats', 'Terry robes'
                ],
                'Floor Coverings & Textile Mats' => [
                    'Textile floor mats', 'Bath mats', 'Door mats', 'Rugs', 'Carpets',
                    'Area rugs', 'Floor runners', 'Carpet tiles', 'Textile-backed floor coverings',
                    'Prayer rugs', 'Yoga mats made from textile materials'
                ],
                'Table Linen' => [
                    'Tablecloths', 'Table covers', 'Table runners', 'Napkins', 'Placemats',
                    'Coasters', 'Table mats', 'Tea cloths', 'Buffet cloths', 'Tray cloths'
                ],
                'Cushion & Upholstery Articles' => [
                    'Cushion covers', 'Cushion cases', 'Pillow covers', 'Decorative pillows',
                    'Bolster covers', 'Sofa covers', 'Chair covers', 'Seat covers', 'Furniture covers',
                    'Upholstery covers', 'Bean bags', 'Pouf covers'
                ],
                'Kitchen Textiles' => [
                    'Kitchen towels', 'Tea towels', 'Dishcloths', 'Oven mitts', 'Pot holders',
                    'Kitchen gloves', 'Aprons', 'Kitchen curtains', 'Bread bags', 'Reusable food bags', 'Kitchen table covers'
                ],
                'Special Textile Made-ups' => [
                    'Quilted textile articles', 'Patchwork articles', 'Embroidered articles',
                    'Appliqué articles', 'Textile gift bags', 'Textile packaging', 'Textile flags',
                    'Textile banners', 'Textile promotional articles', 'Textile toys', 'Stuffed textile articles'
                ],
                'Decorative Made-ups' => [
                    'Wall hangings', 'Tapestries', 'Decorative banners', 'Pennants',
                    'Decorative runners', 'Decorative cushions', 'Decorative throws', 'Textile ornaments', 'Textile craft articles'
                ],
                'Bags & Textile Containers' => [
                    'Shopping bags', 'Tote bags', 'Carry bags', 'Handbags', 'Shoulder bags',
                    'Backpacks', 'Travel bags', 'Duffle bags', 'Sports bags', 'Beach bags',
                    'Laundry bags', 'Shoe bags', 'Drawstring bags', 'Textile pouches', 'Pencil cases', 'Cosmetic bags', 'Storage bags', 'Garment bags'
                ],
            ],

            'CAT-06' => [
                'Industrial Made-ups' => [
                    'Industrial filters', 'Filter bags', 'Filter cloth', 'Conveyor belts',
                    'Conveyor fabric', 'Textile hoses', 'Industrial covers', 'Protective covers',
                    'Tarpaulins', 'Awnings', 'Tents', 'Sails', 'Parachutes', 'Industrial sacks',
                    'FIBC / bulk bags', 'Woven sacks', 'Sandbags', 'Agricultural covers', 'Shade cloth', 'Agricultural nets'
                ],
                'Medical & Hygiene Made-ups' => [
                    'Surgical drapes', 'Surgical sheets', 'Surgical covers', 'Medical bedding',
                    'Hospital sheets', 'Hospital blankets', 'Patient gowns', 'Medical masks',
                    'Textile bandages', 'Gauze articles', 'Dressings', 'Sanitary textile products',
                    'Reusable medical textiles', 'Compression articles'
                ],
                'Automotive Textile Made-ups' => [
                    'Car seat covers', 'Vehicle upholstery', 'Headliner fabrics/articles',
                    'Car carpets', 'Floor mats', 'Boot liners', 'Vehicle covers', 'Sunshades',
                    'Automotive textile filters', 'Airbag textile articles'
                ],
                'Cleaning Textiles' => [
                    'Cleaning cloths', 'Dusting cloths', 'Floor cloths', 'Mop heads',
                    'Mop cloths', 'Microfiber cleaning cloths', 'Wiping cloths', 'Polishing cloths',
                    'Industrial wiping cloths', 'Kitchen cleaning cloths'
                ],
                'Travel & Outdoor Made-ups' => [
                    'Sleeping bags', 'Tents', 'Camping textile articles', 'Backpacks',
                    'Travel organizers', 'Travel blankets', 'Picnic blankets', 'Beach mats',
                    'Sunshades', 'Awnings', 'Tarpaulins', 'Hammocks'
                ],
            ],
        ];

        foreach ($hierarchy as $catCode => $subcats) {
            $cat = $categories[$catCode];
            $subOrder = 1;

            foreach ($subcats as $subName => $products) {
                $sub = TextileSubcategory::updateOrCreate(
                    [
                        'category_id' => $cat->id,
                        'slug' => Str::slug($subName),
                    ],
                    [
                        'name' => $subName,
                        'sort_order' => $subOrder++,
                        'is_active' => true,
                    ]
                );

                $prodOrder = 1;
                foreach ($products as $prodName) {
                    // Generate synonyms for smart search
                    $synonyms = [strtolower($prodName)];
                    if (str_contains($prodName, '/')) {
                        foreach (explode('/', $prodName) as $part) {
                            $synonyms[] = strtolower(trim($part));
                        }
                    }
                    if (str_ends_with(strtolower($prodName), 's')) {
                        $synonyms[] = substr(strtolower($prodName), 0, -1);
                    } else {
                        $synonyms[] = strtolower($prodName) . 's';
                    }

                    TextileProduct::updateOrCreate(
                        [
                            'subcategory_id' => $sub->id,
                            'slug' => Str::slug($prodName),
                        ],
                        [
                            'name' => $prodName,
                            'synonyms' => implode(', ', array_unique($synonyms)),
                            'sort_order' => $prodOrder++,
                            'is_active' => true,
                        ]
                    );
                }
            }
        }

        // ─────────────────────────────────────────────────────────────
        // 4. TECHNICAL MASTERS: FIBRES
        // ─────────────────────────────────────────────────────────────
        $fibres = [
            'Natural Plant' => ['Cotton', 'Flax', 'Linen', 'Hemp', 'Jute', 'Ramie', 'Kenaf', 'Sunn', 'Nettle', 'Sisal', 'Pineapple', 'Coir', 'Coconut', 'Banana', 'Bamboo', 'Okra'],
            'Natural Animal' => ['Sheep wool', 'Merino wool', 'Australian wool', 'Cashmere', 'Mohair', 'Mulberry silk', 'Tussar silk', 'Eri silk', 'Muga silk', 'Pashmina'],
            'Regenerated' => ['Viscose rayon', 'Viscose staple', 'Viscose filament', 'Lyocell', 'Modal', 'Polynosic rayon', 'Cuprammonium rayon'],
            'Acetate' => ['Acetate', 'Cellulose acetate', 'Acetate filament', 'Acetate staple', 'Triacetate'],
            'Synthetic' => ['Polyester (PET)', 'Nylon 6', 'Nylon 66', 'Acrylic', 'Modacrylic', 'Elastane', 'Spandex', 'Polypropylene', 'Polyethylene'],
            'Recycled' => ['Recycled cotton', 'Recycled wool', 'Recycled polyester (rPET)', 'Recycled nylon', 'Recycled acrylic', 'Recycled polypropylene', 'Recycled cellulose'],
            'High-Performance' => ['Aramid (Kevlar/Nomex)', 'PTFE', 'Carbon fibre', 'Glass fibre (E-Glass/S-Glass)', 'UHMWPE', 'Ceramic fibre'],
        ];

        $order = 1;
        foreach ($fibres as $grp => $items) {
            foreach ($items as $name) {
                FibreMaster::updateOrCreate(
                    ['slug' => Str::slug($name)],
                    [
                        'group_name' => $grp,
                        'name' => $name,
                        'sort_order' => $order++,
                        'is_active' => true,
                    ]
                );
            }
        }

        // ─────────────────────────────────────────────────────────────
        // 5. TECHNICAL MASTERS: YARNS
        // ─────────────────────────────────────────────────────────────
        $yarns = [
            'Spun' => ['Ring-Spun Yarn', 'Compact-Spun Yarn', 'Open-End / Rotor Yarn', 'Vortex-Spun Yarn', 'Carded Yarn', 'Combed Yarn'],
            'Filament' => ['Monofilament Yarn', 'Multifilament Yarn', 'POY (Partially Oriented Yarn)', 'FDY (Fully Drawn Yarn)', 'DTY (Drawn Textured Yarn)', 'ATY (Air Textured Yarn)', 'HTY (High Tenacity Yarn)'],
            'Twisted & Ply' => ['Single Yarn', '2-Ply Yarn', '3-Ply Yarn', 'Cabled Yarn', 'Cord Yarn'],
            'Fancy & Textured' => ['Slub Yarn', 'Nep Yarn', 'Chenille Yarn', 'Melange Yarn', 'Metallic / Lurex Yarn', 'Space-Dyed Yarn'],
            'Functional & Covered' => ['Core-Spun Yarn', 'Spandex Covered Yarn', 'High-Tenacity Yarn', 'Flame-Retardant Yarn', 'Antibacterial Yarn', 'Antistatic Yarn'],
            'End-Use' => ['Sewing Thread', 'Embroidery Yarn', 'Knitting Yarn', 'Weaving Yarn', 'Carpet Yarn'],
        ];

        $order = 1;
        foreach ($yarns as $grp => $items) {
            foreach ($items as $name) {
                YarnMaster::updateOrCreate(
                    ['slug' => Str::slug($name)],
                    [
                        'group_name' => $grp,
                        'name' => $name,
                        'sort_order' => $order++,
                        'is_active' => true,
                    ]
                );
            }
        }

        // ─────────────────────────────────────────────────────────────
        // 6. TECHNICAL MASTERS: FABRICS
        // ─────────────────────────────────────────────────────────────
        $fabrics = [
            'Woven' => ['Plain / Tabby', 'Poplin', 'Voile', 'Muslin', 'Cambric', 'Lawn', 'Percale', 'Twill', 'Denim', 'Satin', 'Sateen', 'Taffeta', 'Oxford', 'Canvas', 'Chiffon', 'Georgette', 'Organza', 'Jacquard Fabric', 'Dobby Fabric'],
            'Knitted' => ['Single Jersey', 'Double Jersey', 'Interlock', 'Rib Knit', 'Piqué', 'French Terry', 'Fleece', 'Ponte de Roma', 'Tricot', 'Raschel'],
            'Pile & Terry' => ['Terry Towel Fabric', 'Velour', 'Velvet', 'Corduroy', 'Plush'],
            'Non-Woven' => ['Spunbond', 'Meltblown', 'Needle-Punched Felt', 'Air-Laid', 'Wet-Laid'],
            'Coated & Laminated' => ['PU-Coated', 'PVC-Coated', 'Silicone-Coated', 'Breathable Membrane Laminated', 'TPU Laminated'],
            'Traditional / Handloom' => ['Khadi', 'Banarasi Brocade', 'Chanderi', 'Maheshwari', 'Jamdani', 'Ikat', 'Patola', 'Paithani', 'Kalamkari'],
        ];

        $order = 1;
        foreach ($fabrics as $grp => $items) {
            foreach ($items as $name) {
                FabricMaster::updateOrCreate(
                    ['slug' => Str::slug($name)],
                    [
                        'group_name' => $grp,
                        'name' => $name,
                        'sort_order' => $order++,
                        'is_active' => true,
                    ]
                );
            }
        }

        // ─────────────────────────────────────────────────────────────
        // 7. TECHNICAL MASTERS: PROCESSES
        // ─────────────────────────────────────────────────────────────
        $processes = [
            'Construction' => ['Woven', 'Warp Knitted', 'Weft Knitted', 'Non-Woven', 'Tufted', 'Braided', 'Felted'],
            'Dyeing' => ['Raw / Greige (Undyed)', 'Yarn-Dyed', 'Piece-Dyed', 'Garment-Dyed', 'Natural / Vegetable-Dyed'],
            'Printing' => ['Digital-Printed', 'Screen-Printed', 'Rotary-Printed', 'Block-Printed', 'Sublimation-Printed', 'Discharge-Printed', 'Pigment-Printed'],
            'Mechanical Finishing' => ['Softened', 'Mercerised', 'Brushed / Napped', 'Calendered', 'Sanforised (Pre-shrunk)', 'Peach Finished'],
            'Functional Finishing' => ['Antimicrobial / Antibacterial Finished', 'Flame-Retardant Finished', 'Water-Repellent (DWR) Finished', 'Stain-Resistant Finished', 'UV-Protective Finished', 'Antistatic Finished'],
            'Coating & Lamination' => ['PVC-Coated', 'PU-Coated', 'Rubber-Coated', 'Breathable Film-Laminated'],
        ];

        $order = 1;
        foreach ($processes as $grp => $items) {
            foreach ($items as $name) {
                ProcessMaster::updateOrCreate(
                    ['slug' => Str::slug($name)],
                    [
                        'group_name' => $grp,
                        'name' => $name,
                        'sort_order' => $order++,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
