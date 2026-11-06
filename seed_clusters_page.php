<?php

use App\Models\Page;
use App\Models\Section;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$page = Page::where('slug', 'clusters')->first();
if (!$page) {
    echo "Clusters page not found, creating it...\n";
    $page = Page::create(['slug' => 'clusters', 'title' => 'Major Textile Clusters — TEFC']);
}

// Clear old clusters sections
$page->sections()->delete();

// Default 6 Clusters Data Array
$clustersItems = [
  "Karur" => [
    "name" => "Karur",
    "state" => "Tamil Nadu",
    "spec" => "Home Textiles",
    "era" => "Late 19th Century",
    "exportVal" => "Rs.4,000 Cr+",
    "exportNum" => 4000,
    "units" => "1,500+",
    "workforce" => "1.5 Lakh+",
    "dests" => "60+",
    "desc" => "Karur is known as the \"Textile Capital of Tamil Nadu\", renowned for its production of high-quality home textiles catering to domestic and international markets. The cluster specializes in bed linens, table linens, kitchen linens, and home decor items. This cluster has played a pivotal role in India's export economy with strong buyer networks across the USA, UK, Germany, and the Middle East. It houses over 1,500 manufacturing units, many of which are ISO and OEKO-TEX certified.",
    "highlights" => ["Bed Sheets", "Curtains", "Table Linen", "Cushion Covers"],
    "gradient" => "linear-gradient(135deg, #1e3c72 0%, #2a5298 100%)",
    "chartCurr" => [40, 45, 52, 48, 60, 58, 65, 70, 72, 68, 75, 80],
    "chartPrev" => [35, 38, 42, 40, 48, 45, 50, 55, 58, 52, 60, 65],
    "exporters" => [
      ["name" => "AGC Textiles Pvt. Ltd.", "owner" => "Amit Kumar", "est" => "2003", "exports" => ["USA", "UK", "Germany", "France"], "certs" => ["ISO 9001", "OEKO-TEX"], "prods" => ["Bed Sheets", "Curtains", "Table Linen"]],
      ["name" => "Sri Lakshmi Home Textiles", "owner" => "Meera Subramaniam", "est" => "1998", "exports" => ["UAE", "France", "Japan"], "certs" => ["BCI", "OEKO-TEX"], "prods" => ["Table Linen", "Kitchen Textiles", "Napkins"]],
      ["name" => "Karur Crown Exports", "owner" => "Senthil Kumar", "est" => "2005", "exports" => ["Australia", "Canada", "New Zealand"], "certs" => ["ISO 14001"], "prods" => ["Cushion Covers", "Bed Sheets", "Quilts"]],
      ["name" => "Royal Cotton Mills", "owner" => "Gopa Raju", "est" => "1990", "exports" => ["Italy", "Spain", "Portugal"], "certs" => ["ISO 9001", "GOTS"], "prods" => ["Bath Towels", "Kitchen Linen", "Bedcovers"]],
      ["name" => "Kaviri Weaves & Exports", "owner" => "Rajan Hari", "est" => "2011", "exports" => ["Netherlands", "Sweden", "Denmark"], "certs" => ["BENEFICIAL"], "prods" => ["Woven Pillows", "Table Covers", "Wall Hangings"]]
    ],
    "address" => "TEFC Office, Hill 15, Textile Park, Karur — 639 002, Tamil Nadu",
    "phone" => "+91 4324 123456",
    "email" => "karur@tefc.gov.in",
    "mapUrl" => "https://maps.google.com/?q=Karur+Textile+Park"
  ],
  "Surat" => [
    "name" => "Surat",
    "state" => "Gujarat",
    "spec" => "Synthetic Textiles",
    "era" => "Mid 20th Century",
    "exportVal" => "Rs.6,500 Cr+",
    "exportNum" => 6500,
    "units" => "3,000+",
    "workforce" => "3 Lakh+",
    "dests" => "80+",
    "desc" => "Surat is globally recognized as the \"Synthetic Textile Capital of India\". It excels in synthetic yarn, polyester weaving, and processing. The cluster produces an immense volume of synthetic fabrics, sarees, and dress materials, leveraging state-of-the-art power looms and high-speed digital printing. It accounts for a substantial share of national man-made fiber exports.",
    "highlights" => ["Sarees", "Dress Material", "Polyester", "Embroidery"],
    "gradient" => "linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%)",
    "chartCurr" => [60, 68, 72, 70, 85, 80, 92, 98, 100, 95, 105, 110],
    "chartPrev" => [50, 55, 60, 58, 70, 68, 75, 80, 82, 78, 88, 90],
    "exporters" => [
      ["name" => "Surat Synthetics Ltd.", "owner" => "Rajesh Shah", "est" => "1995", "exports" => ["UAE", "USA", "Europe"], "certs" => ["ISO 9001"], "prods" => ["Sarees", "Polyester"]],
      ["name" => "Kohinoor Prints", "owner" => "Vijay Mehta", "est" => "2002", "exports" => ["Bangladesh", "Africa"], "certs" => ["BCI"], "prods" => ["Dress Material", "Printed Fabrics"]],
      ["name" => "Ambika Weaves", "owner" => "Ramesh Patel", "est" => "2008", "exports" => ["Canada", "UK"], "certs" => ["OEKO-TEX"], "prods" => ["Polyester Fabrics", "Georgette"]],
      ["name" => "Gujarat Jacquards", "owner" => "Dinesh Savani", "est" => "2010", "exports" => ["Middle East", "Southeast Asia"], "certs" => ["ISO 14001"], "prods" => ["Brocades", "Jacquards"]],
      ["name" => "Modern Thread & Zari", "owner" => "Harish Chawla", "est" => "2013", "exports" => ["Sri Lanka", "Nepal"], "certs" => ["GOTS"], "prods" => ["Zari Threads", "Embroidery Fabric"]]
    ],
    "address" => "TEFC Office, Ring Road Textile Market, Surat — 395 002, Gujarat",
    "phone" => "+91 261 2456789",
    "email" => "surat@tefc.gov.in",
    "mapUrl" => "https://maps.google.com/?q=Surat+Textile+Market"
  ],
  "Ichalkaranji" => [
    "name" => "Ichalkaranji",
    "state" => "Maharashtra",
    "spec" => "Woven Fabrics",
    "era" => "Early 20th Century",
    "exportVal" => "Rs.2,500 Cr+",
    "exportNum" => 2500,
    "units" => "20,000+",
    "workforce" => "1 Lakh+",
    "dests" => "40+",
    "desc" => "Known as the \"Manchester of Maharashtra\", Ichalkaranji is a major hub for powerlooms and handlooms. The cluster specializes in cotton yarn, grey fabrics, and high-quality dyed yarns, supplying essential raw materials to garment exporters worldwide. The cooperative spinning mills and automated weaving looms form the backbone of this highly productive cluster.",
    "highlights" => ["Cotton Yarn", "Grey Fabric", "Dyed Yarn", "Canvas"],
    "gradient" => "linear-gradient(135deg, #134e5e 0%, #71b280 100%)",
    "chartCurr" => [25, 28, 30, 29, 35, 34, 38, 40, 42, 38, 45, 48],
    "chartPrev" => [20, 22, 25, 24, 28, 27, 30, 32, 35, 30, 36, 40],
    "exporters" => [
      ["name" => "Deccan Spinners Cooperative", "owner" => "Sangram Patil", "est" => "1988", "exports" => ["China", "Bangladesh"], "certs" => ["ISO 9001"], "prods" => ["Cotton Yarn", "Grey Fabric"]],
      ["name" => "Panchganga Weaves", "owner" => "Ashok Laddha", "est" => "2001", "exports" => ["Sri Lanka", "Vietnam"], "certs" => ["BCI"], "prods" => ["Grey Fabric", "Canvas"]],
      ["name" => "Krishna Fabrics", "owner" => "Vasant Kamble", "est" => "2006", "exports" => ["Egypt", "Turkey"], "certs" => ["OEKO-TEX"], "prods" => ["Dyed Yarn", "Cotton Fabrics"]]
    ],
    "address" => "TEFC Office, Main Road, Ichalkaranji — 416 115, Maharashtra",
    "phone" => "+91 230 2435678",
    "email" => "ichalkaranji@tefc.gov.in",
    "mapUrl" => "https://maps.google.com/?q=Ichalkaranji+Maharashtra"
  ],
  "Jaipur" => [
    "name" => "Jaipur",
    "state" => "Rajasthan",
    "spec" => "Printed Textiles",
    "era" => "18th Century",
    "exportVal" => "Rs.1,200 Cr+",
    "exportNum" => 1200,
    "units" => "1,200+",
    "workforce" => "2 Lakh+",
    "dests" => "50+",
    "desc" => "Jaipur is globally celebrated for its heritage of block printing (Dabu, Sanganeri, Bagru) and traditional tie-dye (Bandhani). The cluster blends artisanal techniques with modern design, creating ethnic and fusion apparel and home furnishings that are highly prized in Western and Asian lifestyle markets.",
    "highlights" => ["Block Print", "Bandhani", "Jaipur Handi", "Quilts"],
    "gradient" => "linear-gradient(135deg, #2c3e50 0%, #3498db 100%)",
    "chartCurr" => [12, 15, 18, 16, 22, 20, 24, 26, 28, 25, 30, 32],
    "chartPrev" => [10, 12, 14, 13, 18, 16, 20, 22, 24, 20, 25, 28],
    "exporters" => [
      ["name" => "Sanganer Heritage Prints", "owner" => "Hari Singh", "est" => "1994", "exports" => ["USA", "Japan"], "certs" => ["GOTS"], "prods" => ["Block Print", "Home Textiles"]],
      ["name" => "Marwar Bandhej", "owner" => "Laxmi Narayan", "est" => "2002", "exports" => ["France", "Spain"], "certs" => ["ISO 9001"], "prods" => ["Bandhani", "Silk Scarves"]],
      ["name" => "Jaipur Royal Quilts", "owner" => "Devendra Ji", "est" => "1985", "exports" => ["UK", "Germany"], "certs" => ["BCI"], "prods" => ["Quilts", "Bedspreads"]]
    ],
    "address" => "TEFC Office, Sanganer Industrial Area, Jaipur — 302 029, Rajasthan",
    "phone" => "+91 141 2736456",
    "email" => "jaipur@tefc.gov.in",
    "mapUrl" => "https://maps.google.com/?q=Sanganer+Jaipur+Rajasthan"
  ],
  "Varanasi" => [
    "name" => "Varanasi",
    "state" => "Uttar Pradesh",
    "spec" => "Silk Weaving",
    "era" => "Ancient / Historic",
    "exportVal" => "Rs.800 Cr+",
    "exportNum" => 800,
    "units" => "1,100+",
    "workforce" => "1.2 Lakh+",
    "dests" => "30+",
    "desc" => "Varanasi is the home of the world-famous Banarasi Saree, an ancient handloom weaving tradition. The weavers employ fine silk yarn and gold/silver zari threads (metallic threads) to create intricate floral and foliate motifs. Highly valued as premium luxury bridal and festive wear.",
    "highlights" => ["Banarasi Saree", "Zari Work", "Brocade", "Silk Scarves"],
    "gradient" => "linear-gradient(135deg, #3a6073 0%, #3a7bd5 100%)",
    "chartCurr" => [8, 10, 12, 11, 15, 14, 16, 18, 20, 17, 22, 25],
    "chartPrev" => [6, 8, 10, 9, 12, 11, 13, 15, 16, 14, 18, 20],
    "exporters" => [
      ["name" => "Kashi Heritage Weaves", "owner" => "Mahadev Prasad", "est" => "1978", "exports" => ["USA", "UAE"], "certs" => ["Silk Mark"], "prods" => ["Banarasi Saree", "Brocade"]],
      ["name" => "Ganga Brocades", "owner" => "Alok Weaver", "est" => "1999", "exports" => ["UK", "Japan"], "certs" => ["ISO 9001"], "prods" => ["Brocade", "Zari Fabric"]],
      ["name" => "Benaras Silk House", "owner" => "Kabir Das", "est" => "2005", "exports" => ["Singapore", "Malaysia"], "certs" => ["Handloom Mark"], "prods" => ["Silk Scarves", "Sarees"]]
    ],
    "address" => "TEFC Office, Chowk Area, Varanasi — 221 001, Uttar Pradesh",
    "phone" => "+91 542 2453678",
    "email" => "varanasi@tefc.gov.in",
    "mapUrl" => "https://maps.google.com/?q=Varanasi+Uttar+Pradesh"
  ],
  "Ludhiana" => [
    "name" => "Ludhiana",
    "state" => "Punjab",
    "spec" => "Knitwear",
    "era" => "Mid 20th Century",
    "exportVal" => "Rs.3,800 Cr+",
    "exportNum" => 3800,
    "units" => "10,000+",
    "workforce" => "4 Lakh+",
    "dests" => "70+",
    "desc" => "Ludhiana is the knitting capital of India, producing over 90% of the country's winter wear. It hosts advanced knitting machines and vertically integrated processing houses, manufacturing premium sweaters, cardigans, thermals, and sportswear for major global brands.",
    "highlights" => ["Sweaters", "Hosiery", "Sportswear", "Thermals"],
    "gradient" => "linear-gradient(135deg, #1d976c 0%, #93f9b9 100%)",
    "chartCurr" => [35, 38, 42, 40, 50, 48, 55, 60, 62, 58, 65, 70],
    "chartPrev" => [30, 32, 36, 35, 42, 40, 48, 52, 55, 50, 58, 62],
    "exporters" => [
      ["name" => "Sutlej Knitwear Ltd.", "owner" => "Gurpreet Singh", "est" => "1982", "exports" => ["UK", "Germany"], "certs" => ["OEKO-TEX", "ISO 9001"], "prods" => ["Sweaters", "Sportswear"]],
      ["name" => "Sher-e-Punjab Hosiery", "owner" => "Baldev Singh", "est" => "1991", "exports" => ["Russia", "Central Asia"], "certs" => ["BCI"], "prods" => ["Hosiery", "Thermals"]],
      ["name" => "Activewear India", "owner" => "Aman Malhotra", "est" => "2006", "exports" => ["USA", "Europe"], "certs" => ["WRAP"], "prods" => ["Sportswear", "Fleece Hoodies"]]
    ],
    "address" => "TEFC Office, Industrial Area-A, Ludhiana — 141 003, Punjab",
    "phone" => "+91 161 2234567",
    "email" => "ludhiana@tefc.gov.in",
    "mapUrl" => "https://maps.google.com/?q=Ludhiana+Punjab"
  ]
];

// Seed section of type clusters_data
$page->sections()->create([
    'title' => 'Cluster Listing Data',
    'type' => 'clusters_data',
    'order' => 1,
    'content' => [
        'subtitle' => 'Explore India\'s 6 major textile export cluster regions — products, exporters, facilities and more.',
        'items' => $clustersItems
    ]
]);

echo "Clusters page seeded successfully with CMS modular parameters!\n";
