import fs from 'fs';
import path from 'path';

function getFiles(dir, files = []) {
  const entries = fs.readdirSync(dir, { withFileTypes: true });
  for (const entry of entries) {
    const fullPath = path.join(dir, entry.name);
    if (entry.isDirectory()) {
      getFiles(fullPath, files);
    } else if (/\.(astro|json|ts|js|css)$/.test(fullPath)) {
      files.push(fullPath);
    }
  }
  return files;
}

const files = getFiles('src');
let changedCount = 0;

for (const file of files) {
  const content = fs.readFileSync(file, 'utf8');
  const newContent = content.replace(/\/images\/([^"'\s`()]+)\.png/g, '/images/$1.webp');
  if (content !== newContent) {
    fs.writeFileSync(file, newContent, 'utf8');
    changedCount++;
    console.log(`Updated ${file}`);
  }
}
console.log(`Updated ${changedCount} files.`);
