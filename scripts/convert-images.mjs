import sharp from 'sharp';
import fs from 'fs/promises';
import path from 'path';

const BRAIN = '/Users/yaronhayo/.gemini/antigravity/brain/29604b25-18cf-41cd-a5bc-821c29c6b05d';
const PUBLIC = '/Users/yaronhayo/Desktop/chimneyV2/public/images';

const filesToConvert = [
  { src: 'chimney_sweep_branded_1771837258315.png', dest: 'services/chimney-sweep.webp' },
  { src: 'chimney_inspection_branded_1771837419620.png', dest: 'services/chimney-inspection.webp' },
  { src: 'chimney_repair_branded_1771837439664.png', dest: 'services/chimney-repair.webp' },
  { src: 'caps_liners_branded_1771837481121.png', dest: 'services/caps-liners.webp' },
  { src: 'fireplace_branded_1771837497925.png', dest: 'services/fireplace-services.webp' },
  { src: 'dryer_vent_branded_1771837532170.png', dest: 'services/dryer-vent.webp' },
  { src: 'hero_technician_branded_1771837583012.png', dest: 'hero/technician.webp' },
  { src: 'process_tech_branded_1771837612311.png', dest: 'backgrounds/process-technician.webp' },
  { src: 'team_james_branded_1771837667391.png', dest: 'team/james.webp' },
  { src: 'team_mike_branded_1771837688698.png', dest: 'team/mike.webp' }
];

async function convertAll() {
  let successCount = 0;
  for (const file of filesToConvert) {
    const inputPath = path.join(BRAIN, file.src);
    const outputPath = path.join(PUBLIC, file.dest);
    
    // Ensure output directory exists
    await fs.mkdir(path.dirname(outputPath), { recursive: true });

    try {
      await sharp(inputPath)
        .webp({ quality: 80 })
        .toFile(outputPath);
      console.log(`✅ Converted ${file.src} to ${file.dest}`);
      successCount++;
    } catch (err) {
      console.error(`❌ Error converting ${file.src}:`, err.message);
    }
  }
  console.log(`\nFinished: ${successCount}/${filesToConvert.length} images converted successfully.`);
}

convertAll();
