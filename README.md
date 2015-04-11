# Symfony Translatable Bundle
This bundle adds a translatable form type which allows you to add multilingual support to your forms.

## Features
 - Unlimites number of locales
 - Two validation method for required fields (all inputs or at least one)
 - Constraints support
 - Empty locales highlightning
 - Tab-key support (and shift+tab as well)
 - Copy-to-other-locales button
 - Foundation 5 integration

## Showcase
#### Text input:
![Traslatable text input](http://i.imgur.com/x732tLL.png)

#### Textarea:
![Traslatable textarea](http://i.imgur.com/knawQ7E.png)

#### CKEditor:
![Tranlatable CKEditor](http://i.imgur.com/B3sV4aa.png)

## Installation
1. Add dependecies to your `composer.json` file
```JSON
{
    "require": {
        "alahtarin/symfony-translatable": "dev-master",
    }
    "repositories": [
      {
        "type" : "vcs",
        "url" : "https://github.com/alahtarin/symfony-translatable.git"
      },
    ]
}
```

2. Enable bundle in `AppKernel.php`
```PHP
 new Alahtarin\Select2Bundle\AlahtarinTranslatableBundle(),
``` 

3. Edit your config file to add new theme template:
```YML
twig:
    form_themes:
      - 'AlahtarinTranslatableBundle:Form:translatable.html.twig'
```

4. Enable bundle configuration:
```YML
  alahtarin_translatable:
      default_locales: %locales%
```
The only parameter is **default_locales**, which is being used when no locales provided explicitly
Should be array of locales, ex: `[en, es]`

5. Include css assets (skip this step if you want to use your custom styles)
```YML
  assetic:
    assets:
      css_vendors:
        inputs:
          - bundles/alahtarintranslatable/css/translatable.css
```
Currently, bundle uses Foundation5 markdown.

## Usage
You can now use the `translatable` form type in your FormBuilder:
```PHP
  $builder->add('name', 'translatable');
```
The bundle will render one form row with label and a text input for each locales, provided in the default_locales parameter.
By default, only the input for the first locale is visible. You can use the buttons to toggle the inputs.
The bundle are generating names for inputs following the scheme **%original_name%[%locale%]**. You can get the submitted values in this way:
```PHP
  $form->get('input_name')->get($locale)
```

### Required parameters:
None, cool!

### Optional parameters:
 - **locales**: array of locales to override the default_locales parameter
 - **field_type** type of the field. Can be any supported form types. Tested ones are **text**, **texrarea**, **ckeditor**
 - **validation_method** could be **one** (at least one field should be filled) or **all** (all of the fields should be filled)
 - **requirements** array of constraints the field should be validated with. Supported are **min** and **max**

## Example
```PHP
$builder->add('lead', 'translatable', [
    'field_type' => 'textarea',
    'required' => true,
    'validation_method' => 'all',
    'requirements' => ['min' => 60, 'max' => '1000']
])
```
