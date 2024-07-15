<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// use Goutte\Client;
// use Symfony\Component\HttpClient\HttpClient;

use GuzzleHttp\Client; 
use Symfony\Component\DomCrawler\Crawler; 

class WebScrappingController extends Controller
{
    public function scrapeEcommerceProducts(Request $request)   
    {
       
        $client = new \Goutte\Client();
        
        $base_url = 'https://www.aliexpress.com/w/wholesale-' . $request->subcategory . '.html';

        // $base_url = 'https://www.aliexpress.com/p/calp-plus/index.html?spm=a2g0o.home.category.1.650c2145NveE5b&categoryTab=computer%252C_office_%2526_education';
        
        $crawler = $client->request('GET', $base_url);

        

        // $crawler = $client->request('GET', $url);

        // Select the div with class "lv3Category--lv3CategoryBox--1Nts99Z"
        // $lv3CategoryDiv = $crawler->filter('img')->each(function ($node){

            // Extract information from the selected div
            // $categoryName = $lv3CategoryDiv->filter('div.lv3Category--lv3CategoryContentName--2JnCa6z')->text();
            // $categoryLink = $lv3CategoryDiv->filter('a')->attr('href');
        //     echo $node->attr('src');
        //     echo "</br>";



        // });

       








        // $crawler->filter('#root')->each(function($node){
        //     // Get the siblings of the current <a> tag
        //     $siblings = $node->siblings();
        
        //     // Loop through the siblings
        //     $siblings->each(function($sibling) {
        //         // Output the HTML of each sibling
        //         echo $sibling->html();
        //         echo "</br>";
        //     });
        // });
        

        // $link = $crawler->selectLink('div.lv3Category--lv3CategoryBox--1Nts99Z a')->link();
        // $crawler = $client->click($link);
        
      

        $response = [];

        for ($page = 1; $page <= 5; $page++) {
            $url = $base_url . '?page=' . $page;
            $crawler = $client->request('GET', $url);


            $crawler->filter(".multi--container--1UZxxHY")->each(function ($node) use (&$response) {

                $product = [];

                // Extract and store the title
                $product['title'] = $node->filter('.multi--content--11nFIBL .multi--title--G7dOCj3 h1')->text();

                // Extract and store the number of sold items
                $node->filter('.multi--content--11nFIBL .multi--trade--Ktbl2jB')->each(function ($node) use (&$product) {
                    $product['sold'] = $node->text();
                });

                // Extract and store the discounted price
                $node->filter('.multi--content--11nFIBL .multi--price-sale--U-S0jtj')->each(function ($node) use (&$product) {
                    $product['discounted_price'] = $node->text();
                });

                // Extract and store the original price
                $product['original_price'] = $node->filter('.multi--content--11nFIBL .multi--price-original--1zEQqOK')->text();

                // Extract and store the discount percentage
                $node->filter('.multi--content--11nFIBL .multi--discount--3hksz5G')->each(function ($node) use (&$product) {
                    $product['discount_percentage'] = $node->text();
                });

                // Extract and store the store name
                $node->filter('.multi--content--11nFIBL > span .cards--store--3GyJcot > a')->each(function ($node) use (&$product) {
                    $product['store_name'] = $node->text();
                });

                // Extract and store the images
                $images = [];
                $node->filter(".images--imageWindow--1Z-J9gn img")->each(function ($imageNode) use (&$images) {
                    $images[] = $imageNode->attr('src');
                });
                $product['images'] = $images;

                // Add the product to the response array
                $response[] = $product;
            });
        }

      
        return $response;
    }




    public function scrapeDarazProducts(Request $request)   
    {
       
        $client = new \Goutte\Client();
        


        //not working 

        // $base_url = 'https://www.daraz.pk/?spm=a2a0e.8553159.header.dhome.51f51b98sMNEbu#';
        // $crawler = $client->request('GET', $base_url);
        // $response = [];

        // for ($page = 1; $page <= 5; $page++) {
        //     $url = $base_url . '?page=' . $page;
        //     $crawler = $client->request('GET', $url);


        //     $crawler->filter(".box--pRqdD.inner--SODwy")->each(function ($node) use (&$response) {

        //         $product = [];

        //         // Extract and store the title
        //         $product['title'] = $node->filter('.info--ifj7U .title--wFj93 a')->text();


        //         // Extract and store the price
        //         $node->filter('.info--ifj7U .price--NVB62 span')->each(function ($node) use (&$product) {
        //             $product['price'] = $node->text();
        //         });

        //         // Extract and store the original price
        //         $product['original_price'] = $node->filter('.info--ifj7U .priceExtra--ocAYk .origPrice--AJxRs del')->text();

        //         // Extract and store the discount percentage
        //         $node->filter('.info--ifj7U .priceExtra--ocAYk span')->each(function ($node) use (&$product) {
        //             $product['discount_percentage'] = $node->text();
        //         });

        //         $response[] = $product;
        //     });
        // }
        // return $response;

        //not working 




        //only header and footer no products 

        // $url = 'https://www.daraz.pk/buy-fresh-produce/?spm=a2a0e.searchlistcategory.cate_1.1.45fc12b8f1ZRId';
        // $crawler = $client->request('GET', $url);
        // $crawler->filter('body')->each(function ($productNode) {
        //     echo $productNode->html();
        // });

        //only header and footer no products 





        // just fetching links 

        // $link = $crawler->filter('.Level_1_Category_No6 .lzd-site-menu-sub-item a')->each(function ($node) {
        //         echo $node->first()->attr("href");
        //         echo "</br>";
        //     });

        // $categories = $crawler->filter('.lzd-site-menu-sub-item a')->each(function ($node) {
        //     echo $node->attr("href");
        //     echo "</br>";
        // });

        // just fetching links 


        //not working 

        // $url = 'https://www.daraz.pk/buy-fresh-fruit/?spm=a2a0e.home.cate_1_1.2.4df14076HEJQ63';

        // $crawler = $client->request('GET', $url);

        // // Select the div with class "info--ifj7U"
        // $infoDiv = $crawler->filter('div.info--ifj7U')->each(function ($node) {;

        //     // Extract information from the selected div
        //     $infoDiv->filter('div.title--wFj93 a')->each(function ($node) {
        //         $title = $node->attr('title');
        //         echo 'Title: ' . $title . PHP_EOL;

        //     });

        //     $infoDiv->filter('div.title--wFj93 a')->each(function ($node) {
        //         $link = $node->attr('href');
        //         echo 'Link: ' . $link . PHP_EOL;

        //     });
        //     $infoDiv->filter('div.price--NVB62 span.currency--GVKjl')->each(function ($node) {
        //         $price =  $node->text();
        //         echo 'Price: ' . $price . PHP_EOL;

        //     });
            
        // });

        //not working 

        

        

        









         $scriptPath = base_path('/scrapeData.js');
         return shell_exec("node $scriptPath");
         return response()->json(['output' => $output]);
    }




    public function testScrapping(Request $req){

        $url = "https://www.daraz.pk/buy-fresh-produce/?page=15&spm=a2a0e.home.cate_1.1.35e34076AzbE55";

        // Create the Guzzle Client instance
        $client = new Client();

        // Create a request to the Daraz URL
        $response = $client->request("GET", $url);

        // Bind the Body DOM elements to the html variable using the Guzzle method
        $html = $response->getBody();

        // Create a Crawler instance using the html binding
        $crawler = new Crawler($html->getContents());
        // Array to store the scraped data
        $scrapedData = [];

        // Filter the Crawler object based on the product container element
        $crawler->filter('.inner--SODwy')->each(function ( $node) use (&$scrapedData) {
            $product = [];
                echo "andar aya";
            // Extract and store the title
            $titleNode = $node->filter('.title--wFj93 a');
            $product['title'] = $titleNode->count() > 0 ? $titleNode->text() : 'N/A';

            // Extract and store the price
            $priceNode = $node->filter('.price--NVB62');
            $product['price'] = $priceNode->count() > 0 ? $priceNode->text() : 'N/A';

            // Extract and store the original price
            $origPriceNode = $node->filter('.priceExtra--ocAYk .origPrice--AJxRs');
            $product['original_price'] = $origPriceNode->count() > 0 ? $origPriceNode->text() : 'N/A';
            echo "andar aya";

            // Extract and store the discount percentage
            $discountNode = $node->filter('.priceExtra--ocAYk span');
            $product['discount_percentage'] = $discountNode->count() > 0 ? $discountNode->text() : 'N/A';

            // Extract and store the shipping information
            $product['free_shipping'] = $node->filter('.freeShipping--l5k4N')->count() > 0;

            // Add the product details to the array
            $scrapedData[] = $product;
        });

        return response()->json([
            'success' => true,
            'message' => 'Scraping successful',
            'data' => $scrapedData,
        ]);
        
    }
}
