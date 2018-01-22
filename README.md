# MVCustom
Architecture MVC PHP préprogrammée 

*Cette dernière contient les méthodes nécessaires à la création d'un simple site. Elle a été simplifiée au maximum afin de comprendre le principe et la création d'un Pattern MVC en PHP.*

## Controller

Le **Controller.php** contenu dans le répertoire `system/core/` et comprenant la classe **Global_Controller** va gérer une partie majoritaire de notre architecture. C'est aussi lui qui fera la liaison entre la partie **Model** et **View**.
Il sera nécessaire de prendre en héritage **Global_Controller** pour chaque controller créé dans `application/controllers/`.

Exemple: (création d'un controller **Example.php** dans `application/controllers/`)

```php
class Example extends Global_Controller {

}
```

**Global_Contoller** a pour rôle de contenir toutes les méthodes de base, utilisables pour toute classe créée par nos soins.
Par exemple, la méthode **view**, qui est la première définie dans **Global_Controller**, consiste à afficher les templates contenus dans le répertoire `view`. Elle peut prendre 2 paramètres, dont un obligatoire: (le nom du fichier view sans l'extension qui par définition sera **.php**) ! Le 2ème paramètre représente un tableau des données que vous voudrez afficher dans votre page (view).


- Exemple: (appel dans notre controller **Example.php**)
```php
public function index() {

    $data['exampleKey'] = 'this is a data !';
    
    $this->view('example', $data);
    
  }
```

- Exemple: (utilisation des données dans la vue **example.php** créée dans `application/views/`)
```html
<p><?php echo $example; ?></p>
```

Autres méthodes comprises dans **Controller.php** :
- **model()**           *inclure un Model*
- **helper()**          *inclure un Helper (fonctions alternatives)*
- **library()**         *inclure une librairie*
- **post($data)**       *récupération d'une valeur en POST*
- **redirect($url)**    *redirection url*
- **previous()**        *redirection page précédente*
- **xss($value)**       *protection xss form*
- **mvcError($error)**  *redirection page d'erreur MVCustom*

### - Accéssibilité URL & Controller

Pour accéder à un Controller que nous avons crée, l'**index.php** à la racine de MVCustom va jouer le rôle de routing. C'est ce fichier qui fera la liaison entre l'url, le controller souhaité ainsi que sa méthode. En effet, pour naviguer sur un site contenant cette architecture, l'url comprendra **`domain.com`**`/`**`Controller`**`/`**`Méthode`** !

**ATTENTION :**
Aucune extension **.php** ou autre n'est requise grace au fichier `.htaccess` à la racine du projet.
Cependant il est impératif d'activer le module d'`URL REWRITING` sur votre serveur web. **!**

### - La méthode index()
**index()** est la première méthode à définir dans un controller. C'est elle qui sera appelée si aucune méthode n'est précisée dans l'URL.

Par exemple: URL => **`domain.com`**`/`**`Produits`**
```php
class Produits extends Global_Controller {

public function index() {

    $this->view('produits');
    
  }
  
  ...
```
Dans cette exemple, l'URL ira chercher le controller **Produits**, et comme aucune méthode n'est précisée, si la méthode **index()** a été définie dans le contoller **Produits**, elle sera automatiquement appelée.
Dans le cas du code ci-dessus, **index.php** affichera le template **produits.php**.

*Il est possible de modifier la méthode par défaut dans :* `system/config/`**`config.php`**

## Model

**ATTENTION !** Avant de commencer cette partie, il est obligatoire de faire un détour dans le fichier **database.php** contenu dans le dossier `system/config/` afin de configurer l'accès à votre base de données. (**database.php** sera inclus dans le constructeur de la class **Global_Model** afin d'établir une connexion à la base de données).

Le **Model.php** contenu dans le répertoire `core` et comprenant la classe **Global_Model** va gérer toute la partie **données**. Il aura pour rôle de contenir toutes les requêtes liées à la base de données.
Il sera nécessaire de prendre en héritage **Global_Model** pour chaque model créé dans `application/models/`.

Exemple: (création d'un model **ExampleModel.php** dans application/models/)
```php
class ExampleModel extends Global_Model {

}
```
Plusieurs setters sont déjà disponibles :
- **select**
- **from**
- **where**
- **order_by**
- **limit**

L'utilisation de votre Model ne sera rien d'autre qu'une succession de **setters** suivi de la méthode contenant la requête !


Exemple: (Création du Model **ExampleModel.php** dans le répertoire `application/models/`)
```php
class ExampleModel extends Global_Model {

    /* ----------------- méthode recupérant des infos ----------------- */

    public function getInfos(limit) {
    
      // setters
      $this->select('*');
      $this->from("posts");
      $this->limit(limit);
      $this->order_by('id_post DESC');
      
      $query = $this->get(); // requête
      
      return $query; // retourne les infos selon les setters ci-dessus
    }
    
    // *************************************************************************
    // equivalence MySQL : "SELECT * from posts ORDER BY id_post DESC LIMIT 100"
    // *************************************************************************

```

Pour récupérer la valeur de retour dans notre Controller **Example.php** il nous pour commencer:
- appeler le Model avec la méthode **model()** (dans le constructeur du Controller si le Model est fréquemment utilisé)
- stocker la méthode du Model (**getInfos()**) en passant par le nom de celui-ci.


Exemple: (**Example.php** dans `application/contollers/`)
```php
class Example extends Global_Controller {
    
    function __construct() {
        
        $this->model('ExampleModel');
        
    }
    
    public function infos() {
    
        $data['infos'] = $this->ExampleModel->getInfos(30);   // 30 modifie le setter "limit"
        
        $this->view('infos', $data);    
    }
}
```

Ainsi, nous accèderons à la page (template) **infos** avec l'URL suivante: **`domain.com`**`/`**`Example`**`/`**`infos`**

**get()** n'est pas la seule méthode disponible dans **Model.php** (Global_Model) :

- **insert($array)**
- **update($data, $idKey, $idValue)**
- **delete($idKey, $idValue)**
- **count()**

