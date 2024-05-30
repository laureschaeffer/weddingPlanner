# Ceremonie Couture

![logo](public/img/logo/C.png)

Application pour une entreprise qui organise des mariages

### Mise en place 

**Avoir une version >= PHP 8.2**
[Download PHP](https://windows.php.net/download#php-8.2)

1. **composer** : [composer](https://getcomposer.org/download/) 

2. **scoop** : dans le powershell
 
``` sh
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser  
```

``` sh
Invoke-RestMethod -Uri https://get.scoop.sh | Invoke-Expression
```

3. **symfony**

``` sh
scoop install symfony-cli
```

Une fois le projet téléchargé, mettre cette ligne dans l'invité de commande pour avoir le bon dossier 'vendor'

``` sh
composer install
```