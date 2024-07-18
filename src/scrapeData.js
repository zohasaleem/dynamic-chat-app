// scrapeData.mjs (note the '.mjs' extension for ESM)
import puppeteer from 'puppeteer';

(async () => {
  const browser = await puppeteer.launch({ headless: true });
  const page = await browser.newPage();

  try {
    await page.goto('https://www.daraz.pk/buy-fresh-produce/?spm=a2a0e.searchlistcategory.cate_1.1.45fc12b8f1ZRId', {
      waitUntil: 'domcontentloaded',
      timeout: 0,
    });

    const products = await page.evaluate(() => {
      const productNodes = document.querySelectorAll('.inner--SODwy');

      const productDetails = Array.from(productNodes).map((node) => {
        const titleNode = node.querySelector('.title--wFj93 a');
        const title = titleNode ? titleNode.innerText : 'N/A';

        // const priceNode = node.querySelector('.price--NVB62 .currency--bOHOc');
        // const price = priceNode ? priceNode.innerText : 'N/A';

        const originalPriceNode = node.querySelector('.origPrice--AJxRs');
        const originalPrice = originalPriceNode ? originalPriceNode.innerText : 'N/A';

        const discountNode = node.querySelector('.discount--HADrg');
        const discount = discountNode ? discountNode.innerText : 'N/A';

        const ratingNode = node.querySelector('.rating--ZI3Ol .icon-star');
        const rating = ratingNode ? ratingNode.length : 0;

        const freeShippingNode = node.querySelector('.freeShipping--l5k4N');
        const freeShipping = freeShippingNode ? true : false;

        return {
          title,
          // price,
          originalPrice,
          discount,
          rating,
          freeShipping,
        };
      });

      return productDetails;
    });

    const apiResponse = {
      success: true,
      message: 'Scraping successful',
      data: products,
    };

    console.log('API Response:', apiResponse);

  } 
  
  catch (error) {
    const apiError = {
      success: false,
      message: 'Error during scraping',
      error: error.message,
    };

    console.error('API Error:', apiError);
  }
  
  finally {
    await browser.close();
  }

})
