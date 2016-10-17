# LaraChileanLaw - Wrapper for leychile.cl web service

Package to facilitate the use of leychile.cl web service using Laravel

# Installation

To add this package on your Laravel project add this on your `composer.json file

```
"unforgivencl/larachileanlaw": "dev-master"
```


To install and configure add the service provider to your `config/app.php`

```Unforgivencl\LaraChileanLaw\LaraChileanLawServiceProvider::class,```


If you want to use the Facade add this to your facade section on `config/app.php`

```'LaraChileanLaw' => Unforgivencl\LaraChileanLaw\Facades\LaraChileanLaw::class,```


# Usage

For example if you want to get the latests 5 laws published you can use

```$laws = LaraChileanLaw::law()->paginate(5)->getLatestPublished()->fetch();```

If you want the law that contains an text with your input you can use

```$laws = LaraChileanLaw::law()->paginate(5)->content('aborto')->getByContent()->fetch();```

If you want a specific law by BCN with the latest version you can use

```$law = LaraChileanLaw::law()->number('1')->getLatestSpecific()->fetch();```

# Response

All responses are converted to JSON from an XML Response, if you see any weird index or order, i'm sorry but
the web service are very incosistent

# Development

This package are under development so if you find any bug feel free to send PR or send an issue.
