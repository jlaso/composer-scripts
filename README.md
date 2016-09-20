# composer-scripts

Composer Scripts is a way to automatize task 
when composer finish updating or installing.

Let's see an example:

We want to install a theme in the vendor forlder but
use it in a source Bundle.

We just have to create a yml file with this structure

```
# app/config/composer-scripts.yml
scripts:
    copy:
        -
            source: vendor/gurayyarar/AdminBSBMaterialDesign/css
            dest: src/Acme/AdminBundle/Resources/public
            method: ln
        -
            source: vendor/gurayyarar/AdminBSBMaterialDesign/images
            dest: src/Acme/AdminBundle/Resources/public
            method: ln
        -
            source: vendor/gurayyarar/AdminBSBMaterialDesign/js
            dest: src/Acme/AdminBundle/Resources/public
            method: ln
```


And declare in the composer.json file the next:

```
{
	...
	"require-dev": {
	    ...
		"jlaso/composer-scripts": "1.0",
		...
	},
	"scripts": {
		"post-install-cmd": [
			...
			"JLaso\\ComposerScripts\\Runner::execute"
		],
		"post-update-cmd": [
	        ...
			"JLaso\\ComposerScripts\\Runner::execute"
		]
	},
	...
	"extra": {
	    ...
		"jlaso-composer-scripts": "app/config/composer-scripts.yml",
		...
	}
}

```

when you launch composer install or composer update at the 
end of the process composer-scripts will create symbolink
links for the folders declared.
