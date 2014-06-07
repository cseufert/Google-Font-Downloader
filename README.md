Google Font Downloader
======================

PHP script for downloading remote fonts in a CSS file.  
*This script is not an example of good programming, it just a small piece of code of a lazy programmer.*


Usage
-----

The PHP script will parser the `style.css` file in the root directory.  
It will look for `@font-face` sections in the stylesheet and download the remote fonts.

The script will download any fonts that have the following definitions:

    @font-face {
        font-family: 'Open Sans';
        font-style: normal;
        font-weight: 300;
        src: local('Open Sans Light'), local('OpenSans-Light'), url(http://themes.googleusercontent.com/static/fonts/opensans/v8/DXI1ORHCpsQm3Vp6mXoaTaRDOzjiPcYnFooOUGCOsRk.woff) format('woff');
    }

Those downloaded fonts will be stored in the `font` folder.  
*If the font from the URL could not be retrieved, the script will add an empty font file.*

Author
------

[Mathias Beke](http://denbeke.be)

Acknowledgements
----------------

This script uses the [PHP CSS Parser](https://github.com/sabberworm/PHP-CSS-Parser)