<?php
namespace Teknomavi\Kargo\Company\Ups\Helper;

use Illuminate\Support\Facades\Log;
use Teknomavi\Common\Text;

class Mapper
{
    protected static $cityMap = [
        'adana'          => 1,
        'adiyaman'       => 2,
        'afyon'          => 3,
        'afyonkarahisar' => 3,
        'agri'           => 4,
        'amasya'         => 5,
        'ankara'         => 6,
        'antalya'        => 7,
        'artvin'         => 8,
        'aydin'          => 9,
        'balikesir'      => 10,
        'bilecik'        => 11,
        'bingol'         => 12,
        'bitlis'         => 13,
        'bolu'           => 14,
        'burdur'         => 15,
        'bursa'          => 16,
        'canakkale'      => 17,
        'cankiri'        => 18,
        'corum'          => 19,
        'denizli'        => 20,
        'diyarbakir'     => 21,
        'edirne'         => 22,
        'elazig'         => 23,
        'erzincan'       => 24,
        'erzurum'        => 25,
        'eskisehir'      => 26,
        'gaziantep'      => 27,
        'giresun'        => 28,
        'gumushane'      => 29,
        'hakkari'        => 30,
        'hatay'          => 31,
        'isparta'        => 32,
        'mersin'         => 33,
        'istanbul'       => 34,
        'izmir'          => 35,
        'kars'           => 36,
        'kastamonu'      => 37,
        'kayseri'        => 38,
        'kirklareli'     => 39,
        'kirsehir'       => 40,
        'kocaeli'        => 41,
        'konya'          => 42,
        'kutahya'        => 43,
        'malatya'        => 44,
        'manisa'         => 45,
        'kahramanmaras'  => 46,
        'mardin'         => 47,
        'mugla'          => 48,
        'mus'            => 49,
        'nevsehir'       => 50,
        'nigde'          => 51,
        'ordu'           => 52,
        'rize'           => 53,
        'sakarya'        => 54,
        'samsun'         => 55,
        'siirt'          => 56,
        'sinop'          => 57,
        'sivas'          => 58,
        'tekirdag'       => 59,
        'tokat'          => 60,
        'trabzon'        => 61,
        'tunceli'        => 62,
        'sanliurfa'      => 63,
        'usak'           => 64,
        'van'            => 65,
        'yozgat'         => 66,
        'zonguldak'      => 67,
        'aksaray'        => 68,
        'bayburt'        => 69,
        'karaman'        => 70,
        'kirikkale'      => 71,
        'batman'         => 72,
        'sirnak'         => 73,
        'bartin'         => 74,
        'ardahan'        => 75,
        'igdir'          => 76,
        'yalova'         => 77,
        'karabuk'        => 78,
        'kilis'          => 79,
        'osmaniye'       => 80,
        'duzce'          => 81,
        'kibris'         => 1000,
    ];
    protected static $townMap = [
        'city1'  => [
            'aladag'     => 12,
            'ceyhan'     => 2,
            'cukurova'   => 5820,
            'feke'       => 3,
            'imamoglu'   => 4,
            'karaisali'  => 5,
            'karatas'    => 6,
            'kozan'      => 7,
            'pozanti'    => 8,
            'saimbeyli'  => 9,
            'saricam'    => 5819,
            'seyhan'     => 1878,
            'tufanbeyli' => 10,
            'yumurtalik' => 11,
            'yuregir'    => 1882,
        ],
        'city2'  => ['besni' => 14, 'celikhan' => 15, 'gerger' => 16, 'golbasi' => 17, 'kahta' => 18, 'merkez' => 13, 'samsat' => 19, 'sincik' => 20, 'tut' => 21],
        'city3'  => [
            'basmakci'   => 36,
            'bayat'      => 37,
            'bolvadin'   => 23,
            'cay'        => 28,
            'cobanlar'   => 38,
            'dazkiri'    => 32,
            'dinar'      => 25,
            'emirdag'    => 27,
            'evciler'    => 39,
            'hocalar'    => 34,
            'ihsaniye'   => 24,
            'kiziloren'  => 40,
            'merkez'     => 22,
            'sandikli'   => 26,
            'sinanpasa'  => 4019,
            'suhut'      => 29,
            'sultandagi' => 31,
            'iscehisar'  => 35,
        ],
        'city4'  => ['diyadin' => 43, 'dogubayazit' => 44, 'eleskirt' => 45, 'hamur' => 46, 'merkez' => 42, 'patnos' => 47, 'taslicay' => 48, 'tutak' => 49],
        'city5'  => ['goynucek' => 51, 'gumushacikoy' => 52, 'hamamozu' => 56, 'merkez' => 50, 'merzifon' => 53, 'suluova' => 54, 'tasova' => 55],
        'city6'  => [
            'akyurt'          => 75,
            'altindag'        => 3998,
            'ayas'            => 58,
            'bala'            => 59,
            'beypazari'       => 60,
            'camlidere'       => 61,
            'cankaya'         => 1698,
            'cubuk'           => 62,
            'elmadag'         => 63,
            'etimesgut'       => 76,
            'evren'           => 77,
            'golbasi'         => 64,
            'gudul'           => 65,
            'haymana'         => 67,
            'kalecik'         => 68,
            'kahramankazan'   => 69,
            'kecioren'        => 4014,
            'kizilcahamam'    => 70,
            'mamak'           => 4017,
            'nallihan'        => 71,
            'polatli'         => 72,
            'pursaklar'       => 5701,
            'sereflikochisar' => 74,
            'sincan'          => 73,
            'yenimahalle'     => 1924,
        ],
        'city7'  => [
            'akseki'     => 81,
            'aksu'       => 82,
            'alanya'     => 79,
            'dosemealti' => 2114,
            'elmali'     => 85,
            'finike'     => 86,
            'gazipasa'   => 87,
            'gundogmus'  => 88,
            'demre'      => 92,
            'kas'        => 93,
            'kemer'      => 94,
            'kepez'      => 4021,
            'konyaalti'  => 4022,
            'korkuteli'  => 95,
            'kumluca'    => 96,
            'manavgat'   => 97,
            'muratpasa'  => 5222,
            'serik'      => 100,
            'ibradi'     => 90,
        ],
        'city8'  => ['ardanuc' => 106, 'arhavi' => 105, 'borcka' => 107, 'hopa' => 109, 'merkez' => 104, 'savsat' => 110, 'yusufeli' => 111, 'murgul' => 108],
        'city9'  => [
            'bozdogan'    => 116,
            'buharkent'   => 118,
            'cine'        => 117,
            'didim'       => 4024,
            'germencik'   => 119,
            'incirliova'  => 122,
            'karacasu'    => 123,
            'karpuzlu'    => 134,
            'kocarli'     => 124,
            'kosk'        => 125,
            'kusadasi'    => 120,
            'kuyucak'     => 121,
            'nazilli'     => 126,
            'soke'        => 128,
            'sultanhisar' => 129,
            'yenipazar'   => 131,
            'efeler'      => 113,
        ],
        'city10' => [
            'ayvalik'   => 140,
            'balya'     => 141,
            'bandirma'  => 142,
            'bigadic'   => 143,
            'burhaniye' => 144,
            'dursunbey' => 145,
            'edremit'   => 147,
            'erdek'     => 148,
            'gomec'     => 161,
            'gonen'     => 149,
            'havran'    => 150,
            'ivrindi'   => 151,
            'kepsut'    => 152,
            'manyas'    => 153,
            'marmara'   => 154,
            'savastepe' => 156,
            'sindirgi'  => 157,
            'susurluk'  => 158,
            'altieylul' => 136,
            'karesi'    => 136,
        ],
        'city11' => ['bozuyuk' => 163, 'golpazari' => 164, 'inhisar' => 169, 'merkez' => 162, 'osmaneli' => 165, 'pazaryeri' => 166, 'sogut' => 167, 'yenipazar' => 168],
        'city12' => ['adakli' => 176, 'genc' => 171, 'karliova' => 172, 'merkez' => 170, 'solhan' => 174, 'yayladere' => 175, 'yedisu' => 177, 'kigi' => 173],
        'city13' => ['adilcevaz' => 179, 'ahlat' => 180, 'hizan' => 182, 'merkez' => 178, 'mutki' => 183, 'tatvan' => 184, 'guroymak' => 181],
        'city14' => ['dortdivan' => 193, 'gerede' => 186, 'goynuk' => 187, 'kibriscik' => 188, 'mengen' => 189, 'merkez' => 185, 'mudurnu' => 190, 'seben' => 191, 'yenicaga' => 192],
        'city15' => [
            'aglasun'    => 196,
            'bucak'      => 197,
            'cavdir'     => 198,
            'celtikci'   => 205,
            'golhisar'   => 200,
            'karamanli'  => 201,
            'kemer'      => 204,
            'merkez'     => 195,
            'tefenni'    => 202,
            'yesilova'   => 203,
            'altinyayla' => 195,
        ],
        'city16' => [
            'buyukorhan'       => 207,
            'gemlik'           => 208,
            'gursu'            => 209,
            'harmancik'        => 210,
            'inegol'           => 211,
            'iznik'            => 212,
            'karacabey'        => 213,
            'keles'            => 214,
            'kestel'           => 215,
            'mudanya'          => 218,
            'mustafakemalpasa' => 219,
            'nilufer'          => 1901,
            'orhaneli'         => 220,
            'orhangazi'        => 221,
            'osmangazi'        => 1908,
            'yenisehir'        => 222,
            'yildirim'         => 1925,
        ],
        'city17' => [
            'ayvacik'  => 224,
            'bayramic' => 225,
            'biga'     => 226,
            'bozcaada' => 228,
            'can'      => 229,
            'eceabat'  => 230,
            'ezine'    => 231,
            'gelibolu' => 232,
            'lapseki'  => 236,
            'merkez'   => 223,
            'yenice'   => 237,
            'gokceada' => 233,
        ],
        'city18' => [
            'atkaracalar' => 245,
            'bayramoren'  => 249,
            'cerkes'      => 239,
            'eldivan'     => 4030,
            'ilgaz'       => 241,
            'kizilirmak'  => 243,
            'korgun'      => 242,
            'kursunlu'    => 244,
            'merkez'      => 238,
            'orta'        => 246,
            'sabanozu'    => 247,
            'yaprakli'    => 248,
        ],
        'city19' => [
            'alaca'     => 251,
            'bayat'     => 252,
            'bogazkale' => 259,
            'dodurga'   => 261,
            'iskilip'   => 253,
            'kargi'     => 254,
            'lacin'     => 263,
            'mecitozu'  => 255,
            'merkez'    => 250,
            'ortakoy'   => 256,
            'osmancik'  => 257,
            'sungurlu'  => 258,
            'ugurludag' => 260,
            'oguzlar'   => 262,
        ],
        'city20' => [
            'acipayam'     => 265,
            'babadag'      => 267,
            'baklan'       => 268,
            'bekilli'      => 269,
            'beyagac'      => 270,
            'bozkurt'      => 271,
            'buldan'       => 272,
            'cal'          => 273,
            'cameli'       => 274,
            'cardak'       => 275,
            'civril'       => 276,
            'guney'        => 277,
            'honaz'        => 278,
            'kale'         => 279,
            'pamukkale'    => 2390,
            'saraykoy'     => 280,
            'serinhisar'   => 281,
            'tavas'        => 282,
            'merkezefendi' => 264,
        ],
        'city21' => [
            'baglar'    => 4031,
            'bismil'    => 284,
            'cermik'    => 286,
            'cinar'     => 289,
            'cungus'    => 292,
            'dicle'     => 291,
            'egil'      => 295,
            'ergani'    => 294,
            'hani'      => 290,
            'hazro'     => 285,
            'kayapinar' => 2456,
            'kocakoy'   => 296,
            'kulp'      => 293,
            'lice'      => 288,
            'silvan'    => 287,
            'sur'       => 4032,
            'yenisehir' => 4033,
        ],
        'city22' => ['enez' => 298, 'havsa' => 299, 'ipsala' => 300, 'kesan' => 302, 'lalapasa' => 303, 'meric' => 304, 'merkez' => 297, 'uzunkopru' => 306, 'suloglu' => 305],
        'city23' => [
            'agin'       => 308,
            'alacakaya'  => 317,
            'aricak'     => 316,
            'baskil'     => 309,
            'karakocan'  => 310,
            'keban'      => 311,
            'kovancilar' => 315,
            'maden'      => 312,
            'merkez'     => 307,
            'palu'       => 313,
            'sivrice'    => 314,
        ],
        'city24' => ['cayirli' => 319, 'ilic' => 320, 'kemah' => 321, 'kemaliye' => 322, 'merkez' => 318, 'otlukbeli' => 326, 'refahiye' => 323, 'tercan' => 324, 'uzumlu' => 327],
        'city25' => [
            'askale'     => 331,
            'cat'        => 333,
            'hinis'      => 334,
            'horasan'    => 335,
            'ispir'      => 336,
            'karacoban'  => 4038,
            'karayazi'   => 337,
            'koprukoy'   => 347,
            'narman'     => 338,
            'oltu'       => 339,
            'olur'       => 340,
            'pasinler'   => 341,
            'pazaryolu'  => 348,
            'senkaya'    => 342,
            'tekman'     => 343,
            'tortum'     => 344,
            'uzundere'   => 346,
            'yakutiye'   => 5062,
            'aziziye'    => 328,
            'palandoken' => 328,
        ],
        'city26' => [
            'alpu'       => 350,
            'cifteler'   => 352,
            'gunyuzu'    => 359,
            'han'        => 360,
            'inonu'      => 353,
            'mahmudiye'  => 354,
            'mihalgazi'  => 361,
            'odunpazari' => 4040,
            'saricakaya' => 356,
            'seyitgazi'  => 357,
            'sivrihisar' => 358,
            'tepebasi'   => 4041,
            'beylikova'  => 351,
            'mihaliccik' => 355,
        ],
        'city27' => ['araban' => 363, 'islahiye' => 365, 'karkamis' => 5233, 'nizip' => 366, 'nurdagi' => 369, 'oguzeli' => 367, 'sahinbey' => 4042, 'sehitkamil' => 1911, 'yavuzeli' => 368],
        'city28' => [
            'alucra'         => 371,
            'bulancak'       => 372,
            'camoluk'        => 382,
            'canakci'        => 383,
            'dereli'         => 373,
            'dogankent'      => 384,
            'espiye'         => 374,
            'eynesil'        => 375,
            'gorele'         => 376,
            'guce'           => 385,
            'kesap'          => 377,
            'merkez'         => 370,
            'piraziz'        => 380,
            'sebinkarahisar' => 378,
            'tirebolu'       => 379,
            'yaglidere'      => 381,
        ],
        'city29' => ['kelkit' => 387, 'kose' => 390, 'kurtun' => 391, 'merkez' => 386, 'siran' => 388, 'torul' => 389],
        'city30' => ['cukurca' => 393, 'merkez' => 392, 'semdinli' => 394, 'yuksekova' => 395],
        'city31' => [
            'altinozu'   => 403,
            'antakya'    => 396,
            'arsuz'      => 2629,
            'belen'      => 407,
            'dortyol'    => 401,
            'erzin'      => 406,
            'hassa'      => 402,
            'iskenderun' => 397,
            'kirikhan'   => 398,
            'kumlu'      => 408,
            'payas'      => 405,
            'reyhanli'   => 399,
            'samandag'   => 404,
            'yayladagi'  => 400,
            'defne'      => 5763,
        ],
        'city32' => [
            'aksu'           => 420,
            'atabey'         => 410,
            'egirdir'        => 411,
            'gelendost'      => 412,
            'gonen'          => 413,
            'keciborlu'      => 414,
            'merkez'         => 409,
            'sarkikaraagac'  => 417,
            'senirkent'      => 415,
            'sutculer'       => 416,
            'uluborlu'       => 418,
            'yalvac'         => 419,
            'yenisarbademli' => 421,
        ],
        'city33' => [
            'akdeniz'    => 422,
            'anamur'     => 425,
            'aydincik'   => 426,
            'bozyazi'    => 427,
            'camliyayla' => 428,
            'erdemli'    => 429,
            'gulnar'     => 430,
            'mezitli'    => 1887,
            'mut'        => 431,
            'silifke'    => 432,
            'tarsus'     => 433,
            'toroslar'   => 424,
            'yenisehir'  => 423,
        ],
        'city34' => [
            'adalar'        => 455,
            'arnavutkoy'    => 1126,
            'atasehir'      => 1722,
            'avcilar'       => 434,
            'bagcilar'      => 437,
            'bahcelievler'  => 438,
            'bakirkoy'      => 435,
            'basaksehir'    => 5824,
            'bayrampasa'    => 436,
            'besiktas'      => 439,
            'beykoz'        => 456,
            'beylikduzu'    => 1077,
            'beyoglu'       => 440,
            'buyukcekmece'  => 441,
            'catalca'       => 442,
            'cekmekoy'      => 1244,
            'esenler'       => 443,
            'esenyurt'      => 5662,
            'eyup'          => 444,
            'fatih'         => 446,
            'gaziosmanpasa' => 447,
            'gungoren'      => 448,
            'kadikoy'       => 457,
            'kagithane'     => 449,
            'kartal'        => 458,
            'kucukcekmece'  => 450,
            'pendik'        => 460,
            'sancaktepe'    => 5823,
            'sariyer'       => 451,
            'sile'          => 462,
            'silivri'       => 452,
            'sisli'         => 453,
            'sultanbeyli'   => 461,
            'tuzla'         => 463,
            'umraniye'      => 464,
            'uskudar'       => 465,
            'zeytinburnu'   => 454,
            'maltepe'       => 459,
            'sultangazi'    => 447,
        ],
        'city35' => [
            'aliaga'      => 474,
            'balcova'     => 4046,
            'bayindir'    => 475,
            'bayrakli'    => 4513,
            'bergama'     => 476,
            'beydag'      => 491,
            'bornova'     => 1879,
            'buca'        => 1900,
            'cesme'       => 477,
            'cigli'       => 4091,
            'dikili'      => 478,
            'foca'        => 479,
            'gaziemir'    => 467,
            'guzelbahce'  => 468,
            'karabaglar'  => 4558,
            'karaburun'   => 480,
            'karsiyaka'   => 1913,
            'kemalpasa'   => 481,
            'kinik'       => 482,
            'kiraz'       => 483,
            'konak'       => 1896,
            'menderes'    => 4666,
            'menemen'     => 484,
            'narlidere'   => 469,
            'odemis'      => 485,
            'seferihisar' => 486,
            'selcuk'      => 487,
            'tire'        => 488,
            'torbali'     => 489,
            'urla'        => 490,
        ],
        'city36' => ['akyaka' => 494, 'arpacay' => 495, 'digor' => 496, 'kagizman' => 497, 'merkez' => 493, 'sarikamis' => 498, 'selim' => 499, 'susuz' => 500],
        'city37' => [
            'abana'       => 502,
            'agli'        => 518,
            'arac'        => 503,
            'azdavay'     => 504,
            'bozkurt'     => 505,
            'catalzeytin' => 507,
            'cide'        => 506,
            'daday'       => 508,
            'devrekani'   => 509,
            'doganyurt'   => 519,
            'ihsangazi'   => 510,
            'inebolu'     => 511,
            'kure'        => 512,
            'merkez'      => 501,
            'pinarbasi'   => 516,
            'senpazar'    => 517,
            'seydiler'    => 513,
            'taskopru'    => 514,
            'tosya'       => 515,
            'hanonu'      => 520,
        ],
        'city38' => [
            'akkisla'    => 532,
            'bunyan'     => 522,
            'develi'     => 523,
            'felahiye'   => 524,
            'hacilar'    => 535,
            'incesu'     => 525,
            'kocasinan'  => 1922,
            'melikgazi'  => 4047,
            'pinarbasi'  => 526,
            'sarioglan'  => 527,
            'sariz'      => 528,
            'talas'      => 533,
            'tomarza'    => 529,
            'yahyali'    => 530,
            'yesilhisar' => 531,
            'ozvatan'    => 534,
        ],
        'city39' => ['babaeski' => 538, 'demirkoy' => 539, 'kofcaz' => 541, 'luleburgaz' => 542, 'merkez' => 536, 'pehlivankoy' => 543, 'pinarhisar' => 544, 'vize' => 545],
        'city40' => ['akcakent' => 551, 'akpinar' => 550, 'boztepe' => 552, 'cicekdagi' => 547, 'kaman' => 548, 'merkez' => 546, 'mucur' => 549],
        'city41' => [
            'basiskele'  => 5818,
            'cayirova'   => 565,
            'darica'     => 555,
            'derince'    => 557,
            'dilovasi'   => 566,
            'gebze'      => 558,
            'golcuk'     => 559,
            'kandira'    => 561,
            'karamursel' => 562,
            'kartepe'    => 5817,
            'korfez'     => 4055,
            'izmit'      => 553,
        ],
        'city42' => [
            'ahirli'     => 590,
            'akoren'     => 587,
            'aksehir'    => 568,
            'altinekin'  => 588,
            'beysehir'   => 569,
            'bozkir'     => 570,
            'celtik'     => 591,
            'cihanbeyli' => 571,
            'cumra'      => 572,
            'derbent'    => 592,
            'derebucak'  => 589,
            'doganhisar' => 574,
            'emirgazi'   => 593,
            'eregli'     => 575,
            'guneysinir' => 594,
            'hadim'      => 576,
            'halkapinar' => 595,
            'huyuk'      => 577,
            'ilgin'      => 578,
            'kadinhani'  => 580,
            'karapinar'  => 581,
            'karatay'    => 1910,
            'kulu'       => 582,
            'meram'      => 4058,
            'sarayonu'   => 583,
            'selcuklu'   => 1918,
            'seydisehir' => 584,
            'taskent'    => 585,
            'tuzlukcu'   => 596,
            'yalihuyuk'  => 597,
            'yunak'      => 586,
        ],
        'city43' => [
            'altintas'    => 599,
            'aslanapa'    => 600,
            'cavdarhisar' => 610,
            'domanic'     => 601,
            'dumlupinar'  => 609,
            'emet'        => 602,
            'gediz'       => 603,
            'hisarcik'    => 606,
            'merkez'      => 598,
            'pazarlar'    => 611,
            'saphane'     => 608,
            'simav'       => 607,
            'tavsanli'    => 604,
        ],
        'city44' => [
            'akcadag'    => 614,
            'arapgir'    => 613,
            'arguvan'    => 615,
            'battalgazi' => 621,
            'darende'    => 617,
            'dogansehir' => 616,
            'doganyol'   => 622,
            'hekimhan'   => 618,
            'kale'       => 623,
            'kuluncak'   => 624,
            'yazihan'    => 625,
            'yesilyurt'  => 620,
            'puturge'    => 619,
        ],
        'city45' => [
            'ahmetli'    => 639,
            'akhisar'    => 627,
            'alasehir'   => 628,
            'demirci'    => 629,
            'golmarmara' => 640,
            'gordes'     => 630,
            'kirkagac'   => 631,
            'koprubasi'  => 641,
            'kula'       => 632,
            'salihli'    => 633,
            'sarigol'    => 634,
            'saruhanli'  => 635,
            'selendi'    => 636,
            'soma'       => 637,
            'turgutlu'   => 638,
            'sehzadeler' => 626,
            'yunusemre'  => 626,
        ],
        'city46' => [
            'afsin'         => 645,
            'andirin'       => 646,
            'caglayancerit' => 653,
            'ekinozu'       => 651,
            'elbistan'      => 647,
            'goksun'        => 648,
            'nurhak'        => 652,
            'pazarcik'      => 649,
            'turkoglu'      => 650,
            'dulkadiroglu'  => 644,
            'onikisubat'    => 644,
        ],
        'city47' => [
            'dargecit'  => 662,
            'derik'     => 660,
            'kiziltepe' => 656,
            'mazidagi'  => 659,
            'midyat'    => 657,
            'nusaybin'  => 655,
            'omerli'    => 658,
            'savur'     => 661,
            'yesilli'   => 663,
            'artuklu'   => 654,
        ],
        'city48' => [
            'bodrum'      => 666,
            'dalaman'     => 667,
            'datca'       => 668,
            'fethiye'     => 669,
            'kavaklidere' => 665,
            'koycegiz'    => 672,
            'marmaris'    => 673,
            'mentese'     => 3304,
            'milas'       => 674,
            'ortaca'      => 675,
            'ula'         => 677,
            'yatagan'     => 678,
            'seydikemer'  => 664,
        ],
        'city49' => ['bulanik' => 682, 'haskoy' => 683, 'korkut' => 686, 'malazgirt' => 684, 'merkez' => 681, 'varto' => 685],
        'city50' => ['acigol' => 698, 'avanos' => 688, 'derinkuyu' => 689, 'gulsehir' => 690, 'hacibektas' => 691, 'kozakli' => 694, 'merkez' => 687, 'urgup' => 696],
        'city51' => ['altunhisar' => 702, 'bor' => 703, 'camardi' => 704, 'merkez' => 701, 'ulukisla' => 5232, 'ciftlik' => 706],
        'city52' => [
            'akkus'      => 708,
            'aybasti'    => 709,
            'camas'      => 721,
            'catalpinar' => 722,
            'caybasi'    => 718,
            'fatsa'      => 710,
            'golkoy'     => 711,
            'gulyali'    => 719,
            'gurgentepe' => 720,
            'ikizce'     => 723,
            'kabaduz'    => 724,
            'kabatas'    => 725,
            'korgan'     => 712,
            'kumru'      => 713,
            'mesudiye'   => 714,
            'persembe'   => 715,
            'ulubey'     => 716,
            'unye'       => 717,
            'altinordu'  => 707,
        ],
        'city53' => [
            'ardesen'     => 727,
            'camlihemsin' => 729,
            'cayeli'      => 728,
            'derepazari'  => 733,
            'findikli'    => 737,
            'guneysu'     => 735,
            'hemsin'      => 739,
            'ikizdere'    => 738,
            'iyidere'     => 734,
            'kalkandere'  => 730,
            'merkez'      => 726,
            'pazar'       => 740,
        ],
        'city54' => [
            'adapazari'  => 741,
            'akyazi'     => 742,
            'arifiye'    => 744,
            'erenler'    => 4070,
            'ferizli'    => 753,
            'geyve'      => 745,
            'hendek'     => 746,
            'karapurcek' => 754,
            'karasu'     => 747,
            'kaynarca'   => 748,
            'kocaali'    => 749,
            'pamukova'   => 750,
            'sapanca'    => 751,
            'sogutlu'    => 755,
            'tarakli'    => 752,
            'serdivan'   => 741,
        ],
        'city55' => [
            'alacam'     => 757,
            'asarcik'    => 765,
            'atakum'     => 4076,
            'ayvacik'    => 769,
            'bafra'      => 758,
            'canik'      => 4077,
            'carsamba'   => 759,
            'havza'      => 760,
            'ilkadim'    => 4079,
            'kavak'      => 761,
            'ladik'      => 762,
            '19-mayis'   => 766,
            'salipazari' => 767,
            'tekkekoy'   => 770,
            'terme'      => 763,
            'vezirkopru' => 764,
            'yakakent'   => 768,
        ],
        'city56' => ['tillo' => 777, 'baykan' => 773, 'eruh' => 776, 'kurtalan' => 774, 'merkez' => 771, 'pervari' => 772, 'sirvan' => 775],
        'city57' => ['ayancik' => 779, 'boyabat' => 780, 'dikmen' => 785, 'duragan' => 781, 'erfelek' => 782, 'gerze' => 783, 'merkez' => 778, 'sarayduzu' => 786, 'turkeli' => 784],
        'city58' => [
            'akincilar'  => 801,
            'altinyayla' => 802,
            'divrigi'    => 788,
            'dogansar'   => 803,
            'gemerek'    => 789,
            'golova'     => 804,
            'gurun'      => 790,
            'hafik'      => 791,
            'imranli'    => 792,
            'kangal'     => 793,
            'koyulhisar' => 794,
            'merkez'     => 787,
            'sarkisla'   => 796,
            'susehri'    => 795,
            'ulas'       => 799,
            'yildizeli'  => 797,
            'zara'       => 798,
        ],
        'city59' => [
            'cerkezkoy'       => 5231,
            'corlu'           => 807,
            'hayrabolu'       => 808,
            'kapakli'         => 3566,
            'malkara'         => 809,
            'marmaraereglisi' => 812,
            'muratli'         => 810,
            'saray'           => 813,
            'sarkoy'          => 814,
            'ergene'          => 805,
            'suleymanpasa'    => 805,
        ],
        'city60' => [
            'almus'      => 817,
            'artova'     => 818,
            'basciftlik' => 826,
            'erbaa'      => 819,
            'merkez'     => 816,
            'niksar'     => 820,
            'pazar'      => 824,
            'resadiye'   => 821,
            'sulusaray'  => 827,
            'turhal'     => 822,
            'yesilyurt'  => 825,
            'zile'       => 823,
        ],
        'city61' => [
            'akcaabat'     => 829,
            'arakli'       => 830,
            'arsin'        => 841,
            'besikduzu'    => 839,
            'carsibasi'    => 844,
            'caykara'      => 842,
            'dernekpazari' => 846,
            'duzkoy'       => 847,
            'hayrat'       => 832,
            'koprubasi'    => 848,
            'macka'        => 838,
            'of'           => 840,
            'salpazari'    => 837,
            'surmene'      => 836,
            'tonya'        => 834,
            'vakfikebir'   => 831,
            'yomra'        => 833,
            'ortahisar'    => 828,
        ],
        'city62' => ['cemisgezek' => 850, 'hozat' => 851, 'mazgirt' => 852, 'merkez' => 849, 'nazimiye' => 853, 'ovacik' => 854, 'pertek' => 856, 'pulumur' => 855],
        'city63' => [
            'akcakale'    => 858,
            'birecik'     => 859,
            'bozova'      => 860,
            'ceylanpinar' => 861,
            'halfeti'     => 862,
            'harran'      => 867,
            'hilvan'      => 863,
            'karakopru'   => 3982,
            'siverek'     => 864,
            'suruc'       => 865,
            'viransehir'  => 866,
            'eyyubiye'    => 857,
            'haliliye'    => 857,
        ],
        'city64' => ['banaz' => 869, 'esme' => 870, 'karahalli' => 871, 'merkez' => 868, 'sivasli' => 872, 'ulubey' => 873],
        'city65' => [
            'bahcesaray' => 884,
            'baskale'    => 875,
            'caldiran'   => 876,
            'catak'      => 877,
            'ercis'      => 878,
            'gevas'      => 879,
            'gurpinar'   => 881,
            'muradiye'   => 882,
            'ozalp'      => 883,
            'saray'      => 886,
            'edremit'    => 885,
            'ipekyolu'   => 874,
            'tusba'      => 874,
        ],
        'city66' => [
            'akdagmadeni' => 888,
            'aydincik'    => 898,
            'bogazliyan'  => 889,
            'candir'      => 890,
            'cayiralan'   => 891,
            'cekerek'     => 892,
            'kadisehri'   => 899,
            'merkez'      => 887,
            'saraykent'   => 900,
            'sarikaya'    => 893,
            'sefaatli'    => 895,
            'sorgun'      => 894,
            'yenifakili'  => 896,
            'yerkoy'      => 897,
        ],
        'city67' => ['alapli' => 902, 'caycuma' => 906, 'devrek' => 907, 'eregli' => 4080, 'gokcebey' => 908, 'kilimli' => 913, 'kozlu' => 915, 'merkez' => 901],
        'city68' => ['agacoren' => 918, 'eskil' => 923, 'guzelyurt' => 919, 'merkez' => 917, 'ortakoy' => 920, 'sariyahsi' => 921, 'gulagac' => 922],
        'city69' => ['aydintepe' => 925, 'demirozu' => 926, 'merkez' => 924],
        'city70' => ['ayranci' => 928, 'basyayla' => 931, 'ermenek' => 929, 'kazimkarabekir' => 930, 'merkez' => 927, 'sariveliler' => 932],
        'city71' => ['bahsili' => 938, 'baliseyh' => 939, 'celebi' => 940, 'delice' => 935, 'karakecili' => 941, 'keskin' => 936, 'merkez' => 933, 'sulakyurt' => 937, 'yahsihan' => 942],
        'city72' => ['besiri' => 944, 'gercus' => 945, 'hasankeyf' => 946, 'kozluk' => 947, 'merkez' => 943, 'sason' => 948],
        'city73' => ['beytussebap' => 950, 'cizre' => 951, 'guclukonak' => 953, 'idil' => 954, 'merkez' => 949, 'silopi' => 955, 'uludere' => 956],
        'city74' => ['amasra' => 958, 'kurucasile' => 960, 'merkez' => 957, 'ulus' => 961],
        'city75' => ['cildir' => 963, 'damal' => 964, 'gole' => 965, 'hanak' => 966, 'merkez' => 962, 'posof' => 967],
        'city76' => ['aralik' => 969, 'karakoyunlu' => 970, 'merkez' => 968, 'tuzluca' => 971],
        'city77' => ['altinova' => 973, 'armutlu' => 974, 'ciftlikkoy' => 976, 'cinarcik' => 975, 'merkez' => 972, 'termal' => 977],
        'city78' => ['eflani' => 979, 'eskipazar' => 980, 'merkez' => 978, 'ovacik' => 981, 'safranbolu' => 982, 'yenice' => 983],
        'city79' => ['elbeyli' => 985, 'merkez' => 984, 'musabeyli' => 986, 'polateli' => 987],
        'city80' => ['bahce' => 989, 'duzici' => 990, 'hasanbeyli' => 991, 'kadirli' => 992, 'merkez' => 988, 'sumbas' => 993, 'toprakkale' => 994],
        'city81' => ['akcakoca' => 996, 'cilimli' => 998, 'cumayeri' => 997, 'golyaka' => 999, 'gumusova' => 5779, 'kaynasli' => 1001, 'merkez' => 995, 'yigilca' => 1002],
    ];

    /**
     * @param string $cityName Şehir adı
     *
     * @throws \InvalidArgumentException
     *
     * @return int
     */
    public static function getCityCode(string $cityName): array
    {
        $slug = Text::createSlug($cityName);
        if (!array_key_exists($slug, self::$cityMap)) {
            Log::debug($cityName ." mevcut şehir listesinde bulunamadı. Lütfen adres bilgisini güncelleyin!");
            //throw new \InvalidArgumentException("$cityName mevcut şehir listesinde bulunamadı");
            return [
                "status" => false,
                "data" => $cityName ." mevcut şehir listesinde bulunamadı. Lütfen adres bilgisini güncelleyin!"
            ];
        }
        return [
            "status" => true,
            "data" => self::$cityMap[$slug]
        ];
    }

    /**
     * @param int    $cityId   Bölge'nin bulunduğu şehir kodu
     * @param string $areaName Bölge adı
     *
     * @throws \InvalidArgumentException
     *
     * @return int
     */
    public static function getAreaCode(int $cityId, string $areaName): array
    {
        $cityCode = 'city' . $cityId;
        if (!array_key_exists($cityCode, self::$townMap)) {
            Log::debug($areaName ." semtinde seçtiğiniz şehir bulunamadı. Lütfen adres bilgisini güncelleyin!");
            //throw new \InvalidArgumentException("$cityId no'lu şehir mevcut bölge listesinde bulunamadı");
            return [
                "status" => false,
                "data" => $areaName ." semtinde seçtiğiniz şehir bulunamadı. Lütfen adres bilgisini güncelleyin!"
            ];
        }
        $slug = Text::createSlug($areaName);
        if (!array_key_exists($slug, self::$townMap[$cityCode])) {
            Log::debug($areaName .", " . "adında bir semt bulunamadı. Lütfen adres bilgisini güncelleyin!");
            //throw new \InvalidArgumentException("$areaName, $cityId no'lu şehire ait mevcut bölge listesinde bulunamadı");
            return [
                "status" => false,
                "data" => $areaName .", " . "adında bir semt bulunamadı. Lütfen adres bilgisini güncelleyin!"
            ];
        }
        return [
            "status" => true,
            "data" => self::$townMap[$cityCode][$slug]
        ];
    }
}
