<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;



class WebScrapController extends Controller
{

    public function scrapeEcommerceProducts(Request $request)   
    {
       
        $client = new \Goutte\Client();
        // $url = 'https://www.aliexpress.com/category/708045/'. $request->subcategory .'.html';
        // $base_url = 'https://www.aliexpress.com/w/wholesale-'.$request->subcategory.'.html';
        // // $base_url = 'https://www.aliexpress.com/w/wholesale-Basket.html?spm=a2g0o.productlist.category.26.7da11ee4fSfkVE&categoryUrlParams=%7B%22q%22%3A%22Basket%22%2C%22s%22%3A%22qp_nw%22%2C%22osf%22%3A%22categoryNagivateOld%22%2C%22sg_search_params%22%3A%22%22%2C%22guide_trace%22%3A%220cacc33d-3fb2-40f4-af0a-4c9903b3aa5c%22%2C%22scene_id%22%3A%2230630%22%2C%22searchBizScene%22%3A%22openSearch%22%2C%22recog_lang%22%3A%22en%22%2C%22bizScene%22%3A%22categoryNagivateOld%22%2C%22guideModule%22%3A%22unknown%22%2C%22postCatIds%22%3A%2215%2C13%22%2C%22scene%22%3A%22category_navigate%22%7D&isFromCategory=y';
        // for ($page = 1; $page <= 5; $page++) {  // Adjust the limit as needed
        //     $url = $base_url . '?page=' . $page;
        //     $crawler = $client->request('GET', $url);
        
        //     $crawler->filter(".multi--container--1UZxxHY")->each(function($node){
            
        //     // Extract and print the title
        //     $title = $node->filter('.multi--content--11nFIBL .multi--title--G7dOCj3 h1')->text();
        //     echo "Title: " . $title . "<br>";
        
        //     // Extract and print the number of sold items
        //     $node->filter('.multi--content--11nFIBL .multi--trade--Ktbl2jB')->each(function($node){
        //         $sold = $node->text();
        //         echo "Sold: " . $sold . "<br>";
        //     });

        //     // Extract and print the discounted price
        //     $discountedPrice = $node->filter('.multi--content--11nFIBL .multi--price-sale--U-S0jtj')->text();
        //     echo "Price: " . $discountedPrice . "<br>";
        
        //     // Extract and print the original price
        //     $originalPrice = $node->filter('.multi--content--11nFIBL .multi--price-original--1zEQqOK')->text();
        //     echo "Price before discount: " . $originalPrice . "<br>";
        
        //     // Extract and print the discount percentage
        //     $node->filter('.multi--content--11nFIBL .multi--discount--3hksz5G')->each(function($node){
        //         $discountPercentage = $node->text();
        //         echo "Discount Percentage: " . $discountPercentage . "<br>";
        //     });

        
        //     // Extract and print the store name
        //     $node->filter('.multi--content--11nFIBL > span .cards--store--3GyJcot > a')->each(function($node){
        //         $storeName = $node->text();
        //         echo "Store Name: " . $storeName . "<br>";
        //     });

        //     echo "Images: " ."<br>";
        //     // $node->filter(".images--imageWindow--1Z-J9gn img")->each(function($node){
        //     //     $inner_url = $node->attr('src');
        //     //     print $inner_url."<br>";
            
        //     // });

        //     $node->filter(".images--imageWindow--1Z-J9gn img")->each(function($imageNode){
        //         $inner_url = $imageNode->attr('src');
        //         echo "Image URL: " . $inner_url . "<br>";
        //     });


        //     echo "<br>";
        // });

            
        // // $baseUrl = 'https://www.aliexpress.com/w/wholesale-Basket.html?';
        // // for ($page = 1; $page <= 1; $page++) {
        // //     // Construct the URL for the current page
        // //     $url = $baseUrl . '&page=' . $page;
        // //     return $url;
        // //     // Make a request to the current URL
        // //     $crawler = $client->request('GET', $url);

        // //     $crawler->filter(".multi--container--1UZxxHY")->each(function($node){
        // //         // Extract and print the title
        // //         $title = $node->filter('.multi--content--11nFIBL .multi--title--G7dOCj3 h1')->text();
        // //         echo "Title: " . $title . "<br>";

        // //         // Your other extraction logic...

        // //         echo "Images: " . "<br>";
        // //         $images = $node->filter(".images--imageWindow--1Z-J9gn img")->extract(['src']);
        // //         foreach ($images as $inner_url) {
        // //             echo "Image URL: " . $inner_url . "<br>";
        // //         }

        // //         echo "<br>";
        // //     });
        // }



        $base_url = 'https://www.aliexpress.com/w/wholesale-' . $request->subcategory . '.html';

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
                $product['discounted_price'] = $node->filter('.multi--content--11nFIBL .multi--price-sale--U-S0jtj')->text();

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










        

        // $catUrl = 'https://www.daraz.pk/?spm=a2a0e.searchlistcategory.header.dhome.5ab7713dnLQOBS#';
        // $url = 'https://www.daraz.pk/buy-fresh-fruit/?spm=a2a0e.home.cate_1_1.2.8d0b4076mlRi25';
        // // $crawler = $client->request('GET', $url);

        //2nd page no outcome
        // $crawler = $client->request('GET', 'https://www.aliexpress.com/item/1005005165866988.html?spm=a2g0o.productlist.main.1.5f955825qfsXkE&algo_pvid=96169c93-c551-4aa7-80be-999237e9c8af&aem_p4p_detail=202311180904534969110001774830002893843&algo_exp_id=96169c93-c551-4aa7-80be-999237e9c8af-0&pdp_npi=4%40dis%21PKR%21302592.81%21199711.26%21%21%217590.00%21%21%402101fb0f17003270934387650e7688%2112000033784710138%21sea%21PK%210%21AB&curPageLogUid=a62obERqPwgR&search_p4p_id=202311180904534969110001774830002893843_1        ');
        // $link = $crawler->filter(".ae-header-content-num")->each(function($node){
        //         echo ($node->text());
        //         echo "</br>";
        //     });






        // $crawler = $client->request('GET', 'https://www.aliexpress.com/category/708042/cpus.html?spm=a2g0o.best.104.5.121f2c25c99J00');
        // // $link = $crawler->filter("#card-list .search-item-card-wrapper-gallery .card--out-wrapper .cards--card--3PJxwBm .multi--content--11nFIBL h1")->each(function($node){

        // $link = $crawler->filter(".multi--content--11nFIBL")->each(function($node){
        //     echo ($node->filter('.multi--title--G7dOCj3 h1')->text());
        //     echo "</br>";
        // });

        // $link = $crawler->selectLink(".multi--container--1UZxxHY .cards--card--3PJxwBm .search-card-item")->link();
        // $crawler = $client->click($link);

        // $crawler->filter('.pdp-info')->each(function ($node) {
        //     print $node->text()."<br>";
        // });


        // $crawler->filter(".multi--container--1UZxxHY")->each(function($node) use ($client) {
        //     $inner_url = $node->attr('href');
        //     print $inner_url."<br>";
        //     echo "</br>";

        //     $innerCrawler = $client->click($node->link());
        //     // print $innerCrawler->html();

        //      $innerCrawler->filter('h1')->each(function ($innerNode) {
        //         echo $innerNode->text();
        //     });
        //     // Extract and print the text content from the '.pdp-info' element on the clicked page
        //     $title = $innerCrawler->filter('.title--wrap--Ms9Zv4A h1[data-pl="product-title"]')->each(function ($innerNode) {

        //         print $innerNode->text() . "<br>";
        //         echo "</br>";

        //     });

        //     echo "<br>";

        //     // $text = $node->text();
        //     // print $text."<br>";
        //     // echo "</br>";

        // });








        // Go to the symfony.com website
        // $crawler = $client->request('GET', 'https://www.symfony.com/blog/');
        // Click on the "Security Advisories" link
        // $link = $crawler->selectLink('Security Advisories')->link();
        // $crawler = $client->click($link);

        // Get the latest post in this category and display the titles
        // $crawler->filter('h2 > a')->each(function ($node) {
        //     print $node->text()."<br>";
        // });

        // $productTitle = $crawler->filter('div.title--wFj93 a')->text();
        // $price = $crawler->filter('div.price--NVB62 span.currency--GVKjl')->text();
        // $location = $crawler->filter('div.rateAndLoc--XWchq span.location--eh0Ro')->attr('title');
    
        // // Displaying the extracted data
        // echo "Product Title: $productTitle<br>";
        // echo "Price: $price<br>";
        // echo "Location: $location<br>";

        // // $titleAttribute = $crawler->filter('.smart-title-standard span')->attr('title');
        // $products = $crawler->filter('div.smart-title-standard span')->each(function ($node) {
        //     return $node->text();
        // });
        

        // foreach ($products as $product) {
        //     echo "Product: $product<br>";
        // }
                // $title = $crawler->filter('h1')->text();


        // $categories = $crawler->filter('.txt-holder')->each(function ($node) {
        //     return $node->text();
        // });

        // $var = 1;
        // foreach ($categories as $category) {
        //     echo "Category " . $var++ . ": $category<br>";
        // }


        
        
    }



    
    // public function scrapeEcommerceProducts(){
    //     $url = 'https://example.com';

    //     $client = new \Goutte\Client();

    //     $crawler = $client->request('GET', $url);

    //     $title = $crawler->filter('h1')->text();
    //     $paragraphs = $crawler->filter('p')->each(function ($node) {
    //         return $node->text();
    //     });
    //     $link = $crawler->filter('a')->attr('href');

    //     echo "Title: $title<br>";
    //     echo "Paragraphs:<br>";
    //     foreach ($paragraphs as $paragraph) {
    //         echo "  - $paragraph<br>";
    //     }
    //     echo "Link: $link<br>";
    
    // }

}
