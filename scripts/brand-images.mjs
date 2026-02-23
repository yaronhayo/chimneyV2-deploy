import fs from 'fs';
import path from 'path';
import sharp from 'sharp';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const SERVICES_DIR = path.resolve(__dirname, '../public/images/services');
const BRAND_LOGO = path.resolve(__dirname, '../public/images/brand/logo.webp');

async function brandImages() {
  try {
    const files = fs.readdirSync(SERVICES_DIR);
    const webpFiles = files.filter((f) => f.endsWith('.webp'));

    console.log(`Found ${webpFiles.length} images to brand.`);

    // Resize logo to be suitable as a watermark
    const logoBuffer = await sharp(BRAND_LOGO)
      .resize({ width: 200 }) // Adjust width as needed
      .webp()
      .toBuffer();

    for (const file of webpFiles) {
      const originalPath = path.join(SERVICES_DIR, file);
      const tempPath = path.join(SERVICES_DIR, `temp-${file}`);

      try {
        await sharp(originalPath)
          .composite([
            {
              input: logoBuffer,
              gravity: 'southeast',
              blend: 'over',
            },
          ])
          .toFile(tempPath);

        // Replace original with branded image
        fs.renameSync(tempPath, originalPath);
        console.log(`Branded: ${file}`);
      } catch (err) {
        console.error(`Failed to brand ${file}: ${err.message}`);
        if (fs.existsSync(tempPath)) fs.unlinkSync(tempPath);
      }
    }

    console.log('Finished branding all images.');
  } catch (error) {
    console.error('Error during branding process:', error);
  }
}

brandImages();
