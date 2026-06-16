const fs = require('fs');
const resizeImg = require('resize-img');
const pngToIco = require('png-to-ico');
 
(async () => {
    let image = await resizeImg(fs.readFileSync('resources/views/build/splash.png'), {
        width: 2732,
        height: 2732
    });
 
    fs.writeFileSync('resources/views/build/cordova-android/resources/splash.png', image);
})();


(async () => {
    let image = await resizeImg(fs.readFileSync('resources/views/build/icon.png'), {
        width: 1024,
        height: 1024
    });
 
    fs.writeFileSync('resources/views/build/cordova-android/resources/icon.png', image);
})();


(async () => {
    let image = await resizeImg(fs.readFileSync('resources/views/build/splash.png'), {
        width: 2732,
        height: 2732
    });
 
    fs.writeFileSync('resources/views/build/cordova-ios/resources/splash.png', image);
})();


(async () => {
    let image = await resizeImg(fs.readFileSync('resources/views/build/icon.png'), {
        width: 1024,
        height: 1024
    });
 
    fs.writeFileSync('resources/views/build/cordova-ios/resources/icon.png', image);
})();


(async () => {
    let image = await resizeImg(fs.readFileSync('resources/views/build/icon.png'), {
        width: 192,
        height: 192
    });
 
    fs.writeFileSync('public/favicons/android-chrome-192x192.png', image);
})();


(async () => {
    let image = await resizeImg(fs.readFileSync('resources/views/build/icon.png'), {
        width: 512,
        height: 512
    });
    
    fs.writeFileSync('public/favicons/android-chrome-512x512.png', image);
})();


(async () => {
    let image = await resizeImg(fs.readFileSync('resources/views/build/icon.png'), {
        width: 180,
        height: 180
    });

    fs.writeFileSync('public/favicons/apple-touch-icon.png', image);
})();

(async () => {
    let image = await resizeImg(fs.readFileSync('resources/views/build/icon.png'), {
        width: 16,
        height: 16
    });

    fs.writeFileSync('public/favicons/favicon-16x16.png', image);
})();

(async () => {
    let image = await resizeImg(fs.readFileSync('resources/views/build/icon.png'), {
        width: 32,
        height: 32
    });

    fs.writeFileSync('public/favicons/favicon-32x32.png', image);

    pngToIco('public/favicons/favicon-32x32.png')
    .then(image => {
      fs.writeFileSync('public/favicons/favicon.ico', image);
    })
    .catch(console.error);

})();

 
 
