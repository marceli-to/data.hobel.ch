<?php

namespace App\Console\Commands;

use App\Models\ProductImage;
use Illuminate\Console\Command;

class UpdateImages extends Command
{
    protected $signature = 'app:update-images';

    protected $description = 'Update product image names';

    public function handle(): int
    {
        $images = [
          [
            'id' => 1,
            'name' => 'horgenglarus-1-380classic-1.jpg'
          ],
          [
            'id' => 2,
            'name' => 'horgenglarus-1-380classic-2.jpg'
          ],
          [
            'id' => 3,
            'name' => 'horgenglarus-1-380classic-3.jpg'
          ],
          [
            'id' => 4,
            'name' => 'horgenglarus-1-380classic-4.jpg'
          ],
          [
            'id' => 5,
            'name' => 'single-in-ahorn-1.jpg'
          ],
          [
            'id' => 6,
            'name' => 'single-in-ahorn-2.jpg'
          ],
          [
            'id' => 7,
            'name' => 'single-in-ahorn-3.jpg'
          ],
          [
            'id' => 8,
            'name' => 'single-in-ahorn-4.jpg'
          ],
          [
            'id' => 9,
            'name' => 'tablett-nussbaum-schwarz-1.jpg'
          ],
          [
            'id' => 10,
            'name' => '1310a-quad.jpg'
          ],
          [
            'id' => 11,
            'name' => '1310b-quad.jpg'
          ],
          [
            'id' => 12,
            'name' => '1310c-quad.jpg'
          ],
          [
            'id' => 13,
            'name' => 'lyra-esprit-6-550.jpg'
          ],
          [
            'id' => 14,
            'name' => '1314a-a.jpg'
          ],
          [
            'id' => 15,
            'name' => '1314b-a.jpg'
          ],
          [
            'id' => 16,
            'name' => '1314c-a.jpg'
          ],
          [
            'id' => 17,
            'name' => '1314d-a.jpg'
          ],
          [
            'id' => 18,
            'name' => '1314e-a.jpg'
          ],
          [
            'id' => 19,
            'name' => '1315a-a.jpg'
          ],
          [
            'id' => 20,
            'name' => '1315b-a.jpg'
          ],
          [
            'id' => 21,
            'name' => '1315c-a.jpg'
          ],
          [
            'id' => 22,
            'name' => '1315d-a.jpg'
          ],
          [
            'id' => 23,
            'name' => '1315e-a.jpg'
          ],
          [
            'id' => 24,
            'name' => '1316a-a.jpg'
          ],
          [
            'id' => 25,
            'name' => '1316b-a.jpg'
          ],
          [
            'id' => 26,
            'name' => '1316c-a.jpg'
          ],
          [
            'id' => 27,
            'name' => '1316d-a.jpg'
          ],
          [
            'id' => 28,
            'name' => '1316e-a.jpg'
          ],
          [
            'id' => 29,
            'name' => '1317a-a.jpg'
          ],
          [
            'id' => 30,
            'name' => '1317b-a.jpg'
          ],
          [
            'id' => 31,
            'name' => '1317c-a.jpg'
          ],
          [
            'id' => 32,
            'name' => '1317d-a.jpg'
          ],
          [
            'id' => 33,
            'name' => '1317e-a.jpg'
          ],
          [
            'id' => 34,
            'name' => '1318a-a.jpg'
          ],
          [
            'id' => 35,
            'name' => '1318b-a.jpg'
          ],
          [
            'id' => 36,
            'name' => '1318c-a.jpg'
          ],
          [
            'id' => 37,
            'name' => '1318d-a.jpg'
          ],
          [
            'id' => 38,
            'name' => '1318e-a.jpg'
          ],
          [
            'id' => 39,
            'name' => '1319a-a.jpg'
          ],
          [
            'id' => 40,
            'name' => '1319b-a.jpg'
          ],
          [
            'id' => 41,
            'name' => '1319c-a.jpg'
          ],
          [
            'id' => 42,
            'name' => '1319d-a.jpg'
          ],
          [
            'id' => 43,
            'name' => '1320a-a.jpg'
          ],
          [
            'id' => 44,
            'name' => '1320b-a.jpg'
          ],
          [
            'id' => 45,
            'name' => '1320c-a.jpg'
          ],
          [
            'id' => 46,
            'name' => '1320d-a.jpg'
          ],
          [
            'id' => 47,
            'name' => '1320e-a.jpg'
          ],
          [
            'id' => 48,
            'name' => 'rahmen-jean-eiche-1.jpg'
          ],
          [
            'id' => 49,
            'name' => 'rahmen-jean-eiche-2.jpg'
          ],
          [
            'id' => 50,
            'name' => 'rahmen-jean-eiche-3.jpg'
          ],
          [
            'id' => 51,
            'name' => 'rahmen-allemann-1.jpg'
          ],
          [
            'id' => 52,
            'name' => 'rahmen-allemann-2.jpg'
          ],
          [
            'id' => 53,
            'name' => 'rahmen-allemann-3.jpg'
          ],
          [
            'id' => 54,
            'name' => 'schneidbrett-hase-1.jpg'
          ],
          [
            'id' => 55,
            'name' => 'schneidbrett-hase-2.jpg'
          ],
          [
            'id' => 56,
            'name' => 'japanisches-geschirr-2.jpg'
          ],
          [
            'id' => 57,
            'name' => 'osterhase-eiche-1.jpg'
          ],
          [
            'id' => 58,
            'name' => 'ballonglser-longdrinkglas-1.jpg'
          ],
          [
            'id' => 59,
            'name' => 'schneidbrett-herz-nussbaum-1.jpg'
          ],
          [
            'id' => 60,
            'name' => 'schneidbrett-herz-nussbaum-2.jpg'
          ],
          [
            'id' => 61,
            'name' => 'schneidbrett-stern-ahorn-1.jpg'
          ],
          [
            'id' => 62,
            'name' => 'schneidbrett-stern-ahorn-2.jpg'
          ],
          [
            'id' => 63,
            'name' => 'holzvase-mit-reagenzglas-aus-altholz-1.jpg'
          ],
          [
            'id' => 64,
            'name' => 'schneidebrett-1.jpg'
          ],
          [
            'id' => 65,
            'name' => 'schneidbrett-mit-griff-konisch-birke-1.jpg'
          ],
          [
            'id' => 66,
            'name' => 'schneidebrett-mit-griff-geschweift-buche-1.jpg'
          ],
          [
            'id' => 67,
            'name' => 'brotbrett-mit-rillen-buche-inkl-messer-1.jpg'
          ],
          [
            'id' => 68,
            'name' => 'brotbrett-mit-rillen-buche-inkl-messer-2.jpg'
          ],
          [
            'id' => 69,
            'name' => 'brotbrett-mit-rillen-buche-inkl-messer-3.jpg'
          ],
          [
            'id' => 70,
            'name' => 'fleischbrett-mit-delle-rille-ahorn-1.jpg'
          ],
          [
            'id' => 71,
            'name' => 'fleischbrett-mit-delle-rille-ahorn-2.jpg'
          ],
          [
            'id' => 72,
            'name' => 'fleischbrett-mit-griffmulde-ahorn-1.jpg'
          ],
          [
            'id' => 73,
            'name' => 'fleischbrett-mit-griffmulde-ahorn-2.jpg'
          ],
          [
            'id' => 74,
            'name' => 'fleischbrett-gross-apfel-1.jpg'
          ],
          [
            'id' => 75,
            'name' => 'schneidebrett-kirsche-stirnholz-1.jpg'
          ],
          [
            'id' => 76,
            'name' => 'schneidebrett-kirsche-stirnholz-2.jpg'
          ],
          [
            'id' => 77,
            'name' => 'hobel-schneidebrett-buche-1.jpg'
          ],
          [
            'id' => 78,
            'name' => 'brett-rund-mit-rille-und-delle-kirschbaum-1.jpg'
          ],
          [
            'id' => 79,
            'name' => 'brett-rund-buche-1.jpg'
          ],
          [
            'id' => 80,
            'name' => 'brett-rund-mit-rille-kirschbaum-1.jpg'
          ],
          [
            'id' => 81,
            'name' => 'brett-rund-mit-griffloch-birke-1.jpg'
          ],
          [
            'id' => 82,
            'name' => 'pizzabrett-mit-griff-kirschbaum-1.jpg'
          ],
          [
            'id' => 83,
            'name' => 'pizzabrett-mit-griff-kirschbaum-2.jpg'
          ],
          [
            'id' => 84,
            'name' => 'cakebrett-kirschbaum-1.jpg'
          ],
          [
            'id' => 85,
            'name' => 'cakebrett-kirschbaum-2.jpg'
          ],
          [
            'id' => 86,
            'name' => 'wandersalamibrett-birnbaum-1.jpg'
          ],
          [
            'id' => 87,
            'name' => 'wandersalamibrett-birnbaum-2.jpg'
          ],
          [
            'id' => 88,
            'name' => 'weinglashalter-kirschbaum-1.jpg'
          ],
          [
            'id' => 89,
            'name' => 'weinglashalter-kirschbaum-2.jpg'
          ],
          [
            'id' => 90,
            'name' => 'pfannenuntersetzer-buche-ahorn-nussbaum-1.jpg'
          ],
          [
            'id' => 91,
            'name' => 'pfannenuntersetzer-buche-ahorn-nussbaum-2.jpg'
          ],
          [
            'id' => 92,
            'name' => 'eierbecher-hagebuche-1.jpg'
          ],
          [
            'id' => 93,
            'name' => 'tablett-kirschbaum-schwarz-1.jpg'
          ],
          [
            'id' => 94,
            'name' => 'tablett-ahorn-blau-1.jpg'
          ],
          [
            'id' => 95,
            'name' => 'tablett-ahorn-rot-1.jpg'
          ],
          [
            'id' => 96,
            'name' => 'tablett-ahorn-1.jpg'
          ],
          [
            'id' => 97,
            'name' => 'tablett-mit-bockli-1.jpg'
          ],
          [
            'id' => 98,
            'name' => 'tablett-mit-bockli-2.jpg'
          ],
          [
            'id' => 99,
            'name' => 'bckli-fr-tablett-hhenverstellbar-eiche-1.jpg'
          ],
          [
            'id' => 100,
            'name' => 'bckli-fr-tablett-hhenverstellbar-eiche-detail-2.jpg'
          ],
          [
            'id' => 101,
            'name' => 'vitrinen-birnbaum-1.jpg'
          ],
          [
            'id' => 102,
            'name' => 'vitrinen-ahorn-schwarz-gebeizt-1.jpg'
          ],
          [
            'id' => 103,
            'name' => 'tannenbaum-mit-sockel-1.jpg'
          ],
          [
            'id' => 104,
            'name' => 'tannenbaum-gesteckt-1.jpg'
          ],
          [
            'id' => 105,
            'name' => 'teelichthalter-stern-eiche-1.jpg'
          ],
          [
            'id' => 106,
            'name' => 'teelichthalter-2-teilig-1.jpg'
          ],
          [
            'id' => 107,
            'name' => 'teelichthalter-herz-1.jpg'
          ],
          [
            'id' => 108,
            'name' => 'stabkerzenhalter-herz-nussbaum-1.jpg'
          ],
          [
            'id' => 109,
            'name' => 'stern-fichte-sageroh-1.jpg'
          ],
          [
            'id' => 110,
            'name' => 'flaschenhalter-zwetschgenbaum-1.jpg'
          ],
          [
            'id' => 111,
            'name' => 'flaschenhalter-zwetschgenbaum-2.jpg'
          ],
          [
            'id' => 112,
            'name' => 'sitzbank-natura-nussbaum-seite-1.jpg'
          ],
          [
            'id' => 113,
            'name' => 'sitzbank-natura-nussbaum-2.jpg'
          ],
          [
            'id' => 114,
            'name' => 'sitzbank-novatur-nussbaum-1.jpg'
          ],
          [
            'id' => 115,
            'name' => 'sitzbank-novatur-nussbaum-frontal-2.jpg'
          ],
          [
            'id' => 116,
            'name' => 'sitzbank-novatur-nussbaum-detail-3.jpg'
          ],
          [
            'id' => 117,
            'name' => 'gartensitzbank-lrche-seitlich-1.jpg'
          ],
          [
            'id' => 118,
            'name' => 'gartensitzbank-lrche-2.jpg'
          ],
          [
            'id' => 119,
            'name' => 'sitzbank-mit-gummieinlage-1.jpg'
          ],
          [
            'id' => 120,
            'name' => 'sitzbank-mit-gummieinlage-frontal-2.jpg'
          ],
          [
            'id' => 121,
            'name' => 'novatur-hocker-ahorn-1.jpg'
          ],
          [
            'id' => 122,
            'name' => 'novatur-hocker-ahorn-detail-2.jpg'
          ],
          [
            'id' => 123,
            'name' => 'novatur-hocker-rund-ahorn-1.jpg'
          ],
          [
            'id' => 124,
            'name' => 'novatur-hocker-rund-ahorn-detail-2.jpg'
          ],
          [
            'id' => 125,
            'name' => 'hocker-backenzahn-nussbaum-1.jpg'
          ],
          [
            'id' => 126,
            'name' => 'hocker-backenzahn-nussbaum-2.jpg'
          ],
          [
            'id' => 127,
            'name' => 'hockerleiter-1-tritt-ahorn-offen-1.jpg'
          ],
          [
            'id' => 128,
            'name' => 'hockerleiter-1-tritt-ahorn-geschlossen-2.jpg'
          ],
          [
            'id' => 129,
            'name' => 'hockerleiter-2-tritt-kirschbaum-offen-1.jpg'
          ],
          [
            'id' => 130,
            'name' => 'hockerleiter-2-tritt-kirschbaum-geschlossen-2.jpg'
          ],
          [
            'id' => 131,
            'name' => 'kchenhocker-glatt-buche-natur-1.jpg'
          ],
          [
            'id' => 132,
            'name' => 'kchenhocker-glatt-rot-gebeizt-1.jpg'
          ],
          [
            'id' => 133,
            'name' => 'kchenhocker-glatt-schwarz-gebeizt-1.jpg'
          ],
          [
            'id' => 134,
            'name' => 'putzkastenhocker-buche-natur-geschlossen-1.jpg'
          ],
          [
            'id' => 135,
            'name' => 'putzkastenhocker-buche-natur-offen-2.jpg'
          ],
          [
            'id' => 136,
            'name' => 'putzkastenhocker-blau-gebeizt-geschlossen-1.jpg'
          ],
          [
            'id' => 137,
            'name' => 'putzkastenhocker-blau-gebeizt-offen-2.jpg'
          ],
          [
            'id' => 138,
            'name' => 'putzkastenhocker-gelb-gebeizt-geschlossen-1.jpg'
          ],
          [
            'id' => 139,
            'name' => 'putzkastenhocker-gelb-gebeizt-offen-2.jpg'
          ],
          [
            'id' => 140,
            'name' => 'fussschemel-buche-natur-1.jpg'
          ],
          [
            'id' => 141,
            'name' => 'fussschemel-blau-gebeizt-1.jpg'
          ],
          [
            'id' => 142,
            'name' => 'fussschemel-rot-gebeizt-1.jpg'
          ],
          [
            'id' => 143,
            'name' => 'fussschemel-orange-gebeizt-1.jpg'
          ],
          [
            'id' => 144,
            'name' => 'postkartenbrett-kirschbaum-2.jpg'
          ],
          [
            'id' => 145,
            'name' => 'postkartenbrett-kirschbaum-1.jpg'
          ],
          [
            'id' => 146,
            'name' => 'postkartenbrett-kirschbaum-3.jpg'
          ],
          [
            'id' => 147,
            'name' => 'schirmstnder-ahorn-nussbaum-1.jpg'
          ],
          [
            'id' => 148,
            'name' => 'sommersitz-fichte-1.jpg'
          ],
          [
            'id' => 149,
            'name' => 'sommersitz-fichte-2.jpg'
          ],
          [
            'id' => 150,
            'name' => 'beistelltisch-zu-sommersitz-fichte-seitlich-1.jpg'
          ],
          [
            'id' => 151,
            'name' => 'beistelltisch-zu-sommersitz-fichte-frontal-2.jpg'
          ],
          [
            'id' => 152,
            'name' => 'beistelltisch-zu-sommersitz-fichte-3.jpg'
          ],
          [
            'id' => 153,
            'name' => 'gartentisch-lrche-seitlich-1.jpg'
          ],
          [
            'id' => 154,
            'name' => 'gartentisch-lrche-frontal-2.jpg'
          ],
          [
            'id' => 155,
            'name' => 'gartentisch-lrche-detail-3.jpg'
          ],
          [
            'id' => 156,
            'name' => 'tisch-la-maisa-ahorn-ruchereiche-setilich-1.jpg'
          ],
          [
            'id' => 157,
            'name' => 'tisch-la-maisa-ahorn-ruchereiche-frontal-2.jpg'
          ],
          [
            'id' => 158,
            'name' => 'tisch-la-maisa-ahorn-ruchereiche-detail-3.jpg'
          ],
          [
            'id' => 159,
            'name' => 'natura-nussbaum-setilich-1.jpg'
          ],
          [
            'id' => 160,
            'name' => 'natura-nussbaum-frontal-2.jpg'
          ],
          [
            'id' => 161,
            'name' => 'natura-nussbaum-3.jpg'
          ],
          [
            'id' => 162,
            'name' => 'natura-nussbaum-detail-4.jpg'
          ],
          [
            'id' => 163,
            'name' => 'natura-nussbaum-detail-5.jpg'
          ],
          [
            'id' => 164,
            'name' => 'natura-nussbaum-detail-6.jpg'
          ],
          [
            'id' => 165,
            'name' => 'natura-nussbaum-detail-7.jpg'
          ],
          [
            'id' => 166,
            'name' => 'natura-nussbaum-detail-8.jpg'
          ],
          [
            'id' => 167,
            'name' => 'natura-nussbaum-detail-9.jpg'
          ],
          [
            'id' => 168,
            'name' => 'natura-nussbaum-oben-10.jpg'
          ],
          [
            'id' => 169,
            'name' => 'tisch-caprea-elsbeer-seitlich-1.jpg'
          ],
          [
            'id' => 170,
            'name' => 'tisch-caprea-elsbeer-2.jpg'
          ],
          [
            'id' => 171,
            'name' => 'tisch-caprea-elsbeer-detail-3.jpg'
          ],
          [
            'id' => 172,
            'name' => 'tisch-caprea-elsbeer-oben-4.jpg'
          ],
          [
            'id' => 173,
            'name' => 'beizentisch-kirschbaum-seitlich-1.jpg'
          ],
          [
            'id' => 174,
            'name' => 'beizentisch-krischbaum.jpg'
          ],
          [
            'id' => 175,
            'name' => 'beizentisch-kirschbaum-detail-3.jpg'
          ],
          [
            'id' => 176,
            'name' => 'linoleumtisch-mit-schwarzem-gestell-setilich-1.jpg'
          ],
          [
            'id' => 177,
            'name' => 'linoleumtisch-mit-schwarzem-gestell-frontal-2.jpg'
          ],
          [
            'id' => 178,
            'name' => 'linoleumtisch-mit-schwarzem-gestell-detail-3.jpg'
          ],
          [
            'id' => 179,
            'name' => 'massivholztisch-mit-cns-fssen-8-8-cm-seite-1.jpg'
          ],
          [
            'id' => 180,
            'name' => 'massivholztisch-mit-cns-fssen-8-8-cm-2.jpg'
          ],
          [
            'id' => 181,
            'name' => 'massivholztisch-mit-cns-fssen-8-8-cm-detail-3.jpg'
          ],
          [
            'id' => 182,
            'name' => 'massivholztisch-mit-cns-fssen-8-8-cm-oben-4.jpg'
          ],
          [
            'id' => 183,
            'name' => '299.jpg'
          ],
          [
            'id' => 184,
            'name' => '299-1.jpg'
          ],
          [
            'id' => 185,
            'name' => 'kofferstnder-ahorn-1.jpg'
          ],
          [
            'id' => 186,
            'name' => 'regal-eiche.jpg'
          ],
          [
            'id' => 187,
            'name' => 'coffee-table-eiche-massiv-1.jpg'
          ],
          [
            'id' => 188,
            'name' => 'coffee-table-kirschbaum-mit-glasplatte-1.jpg'
          ],
          [
            'id' => 189,
            'name' => 'tischli-tripolino-1.jpg'
          ],
          [
            'id' => 190,
            'name' => 'tischli-tripolino-2.jpg'
          ],
          [
            'id' => 191,
            'name' => 'hocker-tisch-tripolino-1.jpg'
          ],
          [
            'id' => 192,
            'name' => 'hocker-tripolino-2.jpg'
          ],
          [
            'id' => 193,
            'name' => 'hocker-tripolino-3.jpg'
          ],
          [
            'id' => 194,
            'name' => 'lehni-klapptisch-w-bsiger-seitlich-1.jpg'
          ],
          [
            'id' => 195,
            'name' => 'lehni-klapptisch-w-bsiger-2.jpg'
          ],
          [
            'id' => 196,
            'name' => 'ulmer-hocker-fichte-natur-1.jpg'
          ],
          [
            'id' => 197,
            'name' => 'ulmer-hocker-fichte-natur-2.jpg'
          ],
          [
            'id' => 198,
            'name' => 'fussteile-liegestuhl-blau-weiss-2.jpg'
          ],
          [
            'id' => 199,
            'name' => 'liegestuh-und-fussteil-blauweiss.jpg'
          ],
          [
            'id' => 200,
            'name' => 'liegestuhl-und-fussteil-gelbweiss.jpg'
          ],
          [
            'id' => 201,
            'name' => 'liegesstuhl-und-fussteil-grunweiss.jpg'
          ],
          [
            'id' => 202,
            'name' => 'liegestuhl-und-fussteil-rotweiss.jpg'
          ],
          [
            'id' => 203,
            'name' => 'fussteile-liegestuhl-blau-weiss-1.jpg'
          ],
          [
            'id' => 204,
            'name' => 'liegestuh-und-fussteil-blauweiss-1.jpg'
          ],
          [
            'id' => 205,
            'name' => 'liegestuhl-und-fussteil-gelbweiss-1.jpg'
          ],
          [
            'id' => 206,
            'name' => 'liegesstuhl-und-fussteil-grunweiss-1.jpg'
          ],
          [
            'id' => 207,
            'name' => 'liegestuhl-und-fussteil-rotweiss-1.jpg'
          ],
          [
            'id' => 208,
            'name' => 'fussteile-liegestuhl-blau-weiss-2-1.jpg'
          ],
          [
            'id' => 209,
            'name' => 'stuhl-laina-nussbaum-1.jpg'
          ],
          [
            'id' => 210,
            'name' => 'stuhl-laina-nussbaum-2.jpg'
          ],
          [
            'id' => 211,
            'name' => 'stuhl-laina-nussbaum-detail-3.jpg'
          ],
          [
            'id' => 212,
            'name' => 'schwab-partner-barhocker-1.jpg'
          ],
          [
            'id' => 213,
            'name' => 'kindertisch-buche-massiv-seitlich-1.jpg'
          ],
          [
            'id' => 214,
            'name' => 'kindertisch-buche-massiv-frontal-2.jpg'
          ],
          [
            'id' => 215,
            'name' => 'kinderstuhl-buche-massiv-1.jpg'
          ],
          [
            'id' => 216,
            'name' => 'kinderstuhl-tisch-buche-massiv-2.jpg'
          ],
          [
            'id' => 217,
            'name' => 'chamleon-design-spiegel-profil-breit-1.jpg'
          ],
          [
            'id' => 218,
            'name' => 'chamleon-design-spiegel-profil-breit-2.jpg'
          ],
          [
            'id' => 219,
            'name' => 'chamleon-design-spiegel-profil-breit-3.jpg'
          ],
          [
            'id' => 220,
            'name' => 'bronzefarben-lackiert-2.jpg'
          ],
          [
            'id' => 221,
            'name' => 'farblos-eloxiert-2.jpg'
          ],
          [
            'id' => 222,
            'name' => 'goldfarben-lackiert-2.jpg'
          ],
          [
            'id' => 223,
            'name' => 'soft-touch-schwarz-2.jpg'
          ],
          [
            'id' => 224,
            'name' => 'chamleon-design-spiegel-profil-schmal-1.jpg'
          ],
          [
            'id' => 225,
            'name' => 'chamleon-design-spiegel-profil-schmal-2.jpg'
          ],
          [
            'id' => 226,
            'name' => 'chamleon-design-spiegel-profil-schmal-3.jpg'
          ],
          [
            'id' => 227,
            'name' => 'bronzefarben-lackiert-1.jpg'
          ],
          [
            'id' => 228,
            'name' => 'farblos-eloxiert-1.jpg'
          ],
          [
            'id' => 229,
            'name' => 'goldfarben-lackiert-1.jpg'
          ],
          [
            'id' => 230,
            'name' => 'stabkerzen-farbig-graublau-dunkel.jpg'
          ],
          [
            'id' => 231,
            'name' => 'stabkerzen-farbig-aubergine-dunkel.jpg'
          ],
          [
            'id' => 232,
            'name' => 'stabkerzen-farbig-koralle.jpg'
          ],
          [
            'id' => 233,
            'name' => 'stabkerzen-farbig-schwarzgrau.jpg'
          ],
          [
            'id' => 234,
            'name' => 'stabkerzen-farbig-weiss.jpg'
          ],
          [
            'id' => 235,
            'name' => 'stabkerzen-farbig-korallrot.jpg'
          ],
          [
            'id' => 236,
            'name' => 'stabkerzen-farbig-schwefelgelb.jpg'
          ],
          [
            'id' => 237,
            'name' => 'stabkerzen-farbig-lachs.jpg'
          ],
          [
            'id' => 238,
            'name' => 'stabkerzen-farbig-eisblau-hell.jpg'
          ],
          [
            'id' => 239,
            'name' => 'stabkerzen-farbig-turkisblau-hell.jpg'
          ],
          [
            'id' => 240,
            'name' => 'filzunterlagen-weiss-grau.jpg'
          ],
          [
            'id' => 241,
            'name' => 'filzunterlagen-rot.jpg'
          ],
          [
            'id' => 242,
            'name' => 'filzunterlagen-weiss.jpg'
          ],
          [
            'id' => 243,
            'name' => 'sola-baguette-besteck-1.jpg'
          ],
          [
            'id' => 244,
            'name' => 'mono-besteck-1.jpg'
          ],
          [
            'id' => 245,
            'name' => 'pott-2733-besteck-1.jpg'
          ],
          [
            'id' => 246,
            'name' => 'stelton-isolierkannen-1.jpg'
          ],
          [
            'id' => 247,
            'name' => 'stelton-teekanne-1.jpg'
          ],
          [
            'id' => 248,
            'name' => 'stelton-teekanne-2.jpg'
          ],
          [
            'id' => 249,
            'name' => 'stelton-thermoisolierkanne-tee-12-lt-1.jpg'
          ],
          [
            'id' => 250,
            'name' => 'stelton-wasserkocher-elektrisch-12-lt-1.jpg'
          ],
          [
            'id' => 251,
            'name' => '298.jpg'
          ],
          [
            'id' => 252,
            'name' => 'pillivuyt-salatschssel-cecil-1.jpg'
          ],
          [
            'id' => 253,
            'name' => 'pillivuyt-saladier-eden-1.jpg'
          ],
          [
            'id' => 254,
            'name' => 'pillivuyt-salatschssel-klassische-form-1.jpg'
          ],
          [
            'id' => 255,
            'name' => 'pillivuyt-gratinform-rechteckig-mit-griffen-1.jpg'
          ],
          [
            'id' => 256,
            'name' => 'ipa-milano-tassen-und-untertassen-1.jpg'
          ],
          [
            'id' => 257,
            'name' => 'ipa-genova-tassen-und-untertassen-1.jpg'
          ],
          [
            'id' => 258,
            'name' => 'pillivuyt-teller-louna-1.jpg'
          ],
          [
            'id' => 259,
            'name' => 'pillivuyt-platten-oval-1.jpg'
          ],
          [
            'id' => 260,
            'name' => 'pillivuyt-gratinierform-sabot-1.jpg'
          ],
          [
            'id' => 261,
            'name' => 'rahmen-hz-alu-nature-6-25-1.jpg'
          ],
          [
            'id' => 262,
            'name' => 'rahmen-hz-alu-nature-6-25-2.jpg'
          ],
          [
            'id' => 263,
            'name' => 'rahmen-hz-alu-nature-6-25-3.jpg'
          ],
          [
            'id' => 264,
            'name' => 'rahmen-hz-eiche-birke-koto-1.jpg'
          ],
          [
            'id' => 265,
            'name' => 'rahmen-hz-eiche-birke-koto-2.jpg'
          ],
          [
            'id' => 266,
            'name' => 'rahmen-hz-eiche-birke-koto-3.jpg'
          ],
          [
            'id' => 267,
            'name' => 'rahmen-hz-eiche-geruchert-1.jpg'
          ],
          [
            'id' => 268,
            'name' => 'rahmen-hz-eiche-geruchert-2.jpg'
          ],
          [
            'id' => 269,
            'name' => 'rahmen-hz-eiche-geruchert-3.jpg'
          ],
          [
            'id' => 270,
            'name' => 'rahmen-hz-ramin-1.jpg'
          ],
          [
            'id' => 271,
            'name' => 'rahmen-hz-ramin-2.jpg'
          ],
          [
            'id' => 272,
            'name' => 'rahmen-hz-ramin-3.jpg'
          ],
          [
            'id' => 273,
            'name' => 'rahmen-lehni-hoch-und-quer-1.jpg'
          ],
          [
            'id' => 274,
            'name' => 'rahmen-lehni-hoch-und-quer-2.jpg'
          ],
          [
            'id' => 275,
            'name' => 'rahmen-lehni-hoch-und-quer-3.jpg'
          ],
          [
            'id' => 276,
            'name' => 'rahmen-hofmnner-hoch-und-quer-1.jpg'
          ],
          [
            'id' => 277,
            'name' => 'rahmen-hofmnner-hoch-und-quer-2.jpg'
          ],
          [
            'id' => 278,
            'name' => 'rahmen-hofmnner-hoch-und-quer-3.jpg'
          ],
          [
            'id' => 279,
            'name' => 'pillivuyt-kuchenform-rund-gerippt-1.jpg'
          ],
          [
            'id' => 280,
            'name' => 'pillivuyt-kuchenform-rund-gerippt-2.jpg'
          ],
          [
            'id' => 281,
            'name' => 'pillivuyt-eden-tassen-und-untertassen-1.jpg'
          ],
          [
            'id' => 282,
            'name' => 'pillivuyt-sancerre-tassen-und-untertassen-1.jpg'
          ],
          [
            'id' => 283,
            'name' => 'pillivuyt-sancerre-milchkrug-1.jpg'
          ],
          [
            'id' => 284,
            'name' => 'pillivuyt-ccil-tassen-und-untertassen-1.jpg'
          ],
          [
            'id' => 285,
            'name' => 'pillivuyt-buttergeschirr-mit-deckel-1.jpg'
          ],
          [
            'id' => 286,
            'name' => 'costa-nova-schalen-und-krug-1.jpg'
          ],
          [
            'id' => 287,
            'name' => 'costa-nova-schalen-und-krug-2.jpg'
          ],
          [
            'id' => 288,
            'name' => 'iittala-teema-teller-und-becher-grau-1.jpg'
          ],
          [
            'id' => 289,
            'name' => 'iittala-teema-geschirr-farbig-1.jpg'
          ],
          [
            'id' => 290,
            'name' => 'iittala-origo-schalen-und-teller-beige-1.jpg'
          ],
          [
            'id' => 291,
            'name' => 'iittala-origo-schalen-und-teller-beige-2.jpg'
          ],
          [
            'id' => 292,
            'name' => 'iittala-origo-geschirr-orange-1.jpg'
          ],
          [
            'id' => 293,
            'name' => 'iittala-origo-geschirr-orange-2.jpg'
          ],
          [
            'id' => 294,
            'name' => 'hergiswiler-lipari-vase-1.jpg'
          ],
          [
            'id' => 295,
            'name' => 'hergiswiler-stromboli-vase-1.jpg'
          ],
          [
            'id' => 296,
            'name' => 'hergiswiler-budelle-1.jpg'
          ],
          [
            'id' => 297,
            'name' => 'hergiswiler-annakelche-1.jpg'
          ],
          [
            'id' => 298,
            'name' => 'hergiswiler-becher-1.jpg'
          ],
          [
            'id' => 299,
            'name' => 'iittala-aino-aalto-becher-022-lt-1.jpg'
          ],
          [
            'id' => 300,
            'name' => 'iittala-aino-aalto-becher-033-lt-2.jpg'
          ],
          [
            'id' => 301,
            'name' => 'tischplatte-rund.jpg'
          ],
          [
            'id' => 302,
            'name' => 'karniesprofil.jpg'
          ],
          [
            'id' => 303,
            'name' => 'tischplatte-rechteckig.jpg'
          ],
          [
            'id' => 304,
            'name' => 'karniesprofil-1.jpg'
          ],
          [
            'id' => 305,
            'name' => 'kchenhocker-lttli-schwarz-gebeizt-1.jpg'
          ],
          [
            'id' => 306,
            'name' => 'kchenhocker-lttli-rot-gebeizt-1.jpg'
          ],
          [
            'id' => 307,
            'name' => 'kchenhocker-lttli-buche-natur-1.jpg'
          ],
          [
            'id' => 308,
            'name' => 'novatur-nussbaum-seite-1.jpg'
          ],
          [
            'id' => 309,
            'name' => 'novatur-nussbaum-2.jpg'
          ],
          [
            'id' => 310,
            'name' => 'novatur-nussbaum-detail-3.jpg'
          ],
          [
            'id' => 311,
            'name' => 'novatur-nussbaum-detail-4.jpg'
          ],
          [
            'id' => 312,
            'name' => 'novatur-nussbaum-2-oben-5.jpg'
          ],
          [
            'id' => 313,
            'name' => 'extenso-eisbuche-seitlich-1.jpg'
          ],
          [
            'id' => 314,
            'name' => 'extenso-eisbuche-2.jpg'
          ],
          [
            'id' => 315,
            'name' => 'extenso-eisbuche-detail-3.jpg'
          ],
          [
            'id' => 316,
            'name' => 'extenso-eisbuche-detail-4.jpg'
          ],
          [
            'id' => 317,
            'name' => 'extenso-eisbuche-detail-5.jpg'
          ],
          [
            'id' => 318,
            'name' => 'extenso-eisbuche-detail-6.jpg'
          ],
          [
            'id' => 319,
            'name' => 'triangolin-1.jpg'
          ],
          [
            'id' => 320,
            'name' => 'triangolin-2.jpg'
          ],
          [
            'id' => 321,
            'name' => 'triangolin-detail-3.jpg'
          ],
          [
            'id' => 322,
            'name' => 'art-322-tablett-eiche-schwarz-1.jpg'
          ],
          [
            'id' => 323,
            'name' => 'art-323-tablett-eiche-eiche-1.jpg'
          ],
          [
            'id' => 324,
            'name' => 'art-324-tablett-nussbaum-nussbaum-1.jpg'
          ],
          [
            'id' => 325,
            'name' => 'art-325-tablett-kirsche-blau-1.jpg'
          ],
          [
            'id' => 326,
            'name' => 'neuer-art-326-tablett-kirsche-kirsche-1.jpg'
          ],
          [
            'id' => 327,
            'name' => 'art-329-tablett-birnbaum-birnbaum-1.jpg'
          ],
          [
            'id' => 328,
            'name' => 'art-3210-tablett-birnbaum-schwarz-1.jpg'
          ],
          [
            'id' => 329,
            'name' => 'art-3214-tablett-ahorn-schwarz-1.jpg'
          ],
          [
            'id' => 330,
            'name' => '1-variante-web.jpg'
          ],
          [
            'id' => 331,
            'name' => '11-variante-web.jpg'
          ],
          [
            'id' => 332,
            'name' => '12-variante-web.jpg'
          ],
          [
            'id' => 333,
            'name' => '13-variante-web.jpg'
          ],
          [
            'id' => 334,
            'name' => 'ladestation.jpg'
          ],
          [
            'id' => 335,
            'name' => '2-variante-scaled.jpg'
          ],
          [
            'id' => 336,
            'name' => '21-variante-web.jpg'
          ],
          [
            'id' => 337,
            'name' => '22-variante-web.jpg'
          ],
          [
            'id' => 338,
            'name' => '23-variante-web.jpg'
          ],
          [
            'id' => 339,
            'name' => 'ladestation-1.jpg'
          ],
          [
            'id' => 340,
            'name' => '3-variante-web.jpg'
          ],
          [
            'id' => 341,
            'name' => '31-variante-web.jpg'
          ],
          [
            'id' => 342,
            'name' => '32-variante-web.jpg'
          ],
          [
            'id' => 343,
            'name' => '33-varinate-web.jpg'
          ],
          [
            'id' => 344,
            'name' => 'ladestation-2.jpg'
          ]
        ];

        foreach ($images as $image) {
          ProductImage::where('id', $image['id'])->update(['name' => $image['name']]);
        }

        $this->info('Updated '.count($images).' images.');

        return Command::SUCCESS;
    }
}
