# Pinball

A Laravel starter-kit to scaffold the application stack used in Lunarstorm software apps, as well as provide a swiss-army-knife collection of commonly used tools and helpers.

This package is an effort based on the range of use cases from software apps built for clients by Lunarstorm since 2011. The aim of this starter-kit is to:

- Improve developer efficiency and experience
- Establish a standard starting point for all client apps to inherit from
- Provide an ecosystem of useful tools and components that apps can leverage
- Continuously evolve based on common use-cases encountered across client apps, in an effort to eliminate unncessary/annoying ad-hoc code where possible

It is an ongoing work in progress, driven by the collective specs of all the apps that use it.

## Installation

Via Composer

First add `vio/pinball` as a custom repository in `composer.json`:

```json
{
  "repositories": {
    "vio/pinball": {
      "type": "vcs",
      "url": "https://bitbucket.org/lunarstorm/pinball.git"
    }
  }
}
```

Then install it:

``` bash
$ composer require vio/pinball
```

## Usage

Install the stack via:

``` bash
$ php artisan pinball:install
```

While this does scaffold and configure almost all things, there are still a couple of loose ends:

- Automated installation of the `vio` frontend peer dependency (Vue3 component and helper library).
- Some form of automation or de-coupling with respect to the paid Bootstrap theme (Looper) that frontend component stubs currently rely on. Pinball is unable to bundle that theme due to the nature of licensing.


## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.


## Credits

- Jasper Tey
