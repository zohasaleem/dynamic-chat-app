import puppeteer from 'puppeteer';

(async () => {
  try {
    const browser = await puppeteer.launch({ headless: true }); // Adjust headless based on your needs
    const page = await browser.newPage();

    const url = 'https://www.aliexpress.com/p/calp-plus/index.html?spm=a2g0o.home.category.1.650c2145NveE5b&categoryTab=computer%252C_office_%2526_education'; // Replace with the actual URL
    await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 0 });

    const scrapedData = await page.evaluate(() => {
      const lv3CategoryDiv = document.querySelector('.lv3Category--lv3CategoryBox--1Nts99Z');

      const productDetails = Array.from(lv3CategoryDiv).map((node) => {

        const categoryName = node.querySelector('.lv3Category--lv3CategoryContentName--2JnCa6z').textContent.trim();
        const categoryLink = node.querySelector('a').getAttribute('href');
        const imageSrc = node.querySelector('div.lv3Category--lv3CategoryContentImg--2GZvdRG img').getAttribute('src');

      return {
        categoryName,
        categoryLink,
        imageSrc,
      };
    });

    return productDetails;
  });

  const apiResponse = {
    success: true,
    message: 'Scraping successful',
    data: scrapedData,
  };

  console.log('API Response:', apiResponse);

    await browser.close();
  } catch (error) {
    console.error(JSON.stringify({ success: false, message: 'Error during scraping', error: error.message }));
  }
})();
