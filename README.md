# UserBundle
Work still in progress.
Will be part of an AdminBundle.
(c) mesclics.fr 2018.

Provides a User class, and some layouts (e.g. login-form) for faster User implementation in future mesclics.fr projects that will use the MesClics\AdminBundle

Use this class for your Users management

1.- create your own User class that extends MesClics\UserBundle\Entity\User and add a Repository reference to your class (if users are loaded from database).

2.- use the right authenticator (LoginFormAuthenticator, ApiAuthenticator...)
```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use MesClics\UserBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MyUserRepository")
 * @ORM\Table(name="mesclics_user2")
 */
class MyUser extends User{
    //add your own logic
}
```

2.- create the Repository class file that handles your User class. By default, the MesClics\UserBundle\UserRepository authorizes the users to log in with their username OR their email. If you want your own User class to have this same behaviour, extends the MesClics\UserBundle\Repository\UserRepository. Else, just implement the UserLoaderInterface to your own Repository class

```php
//you want the MesClics\UserBundle\Repository\User default behaviour (login with username OR email)
<?php

namespace App\Repository;

use MesClics\UserBundle\Repository\UserRepository;
class MyUserRepository extends UserRepository{}
```

```php
//you do not want the MesClics\UserBundle\Repository\User default behaviour (login with username OR email)
<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class MyUserRepository extends EntityRepository implements UserLoaderInterface{
    //add your own logic (e.g : add a custom loadUserByUsername function)
}
```
3.- import the MesClics/UserBundle/Resources/config/routing_security.yml file in your own app routing.yml file
```yml
    mesclics_security:
        resource: "@MesClicsUserBundle/Resources/config/routing_security.yml"
```
4.- modify security.yml to make this new User class to be used by the users provider. 

```yaml
    security:
    encoders:
        App\Entity\MyUser:
            algorithm: bcrypt
            cost: 12

    providers:
        in_memory:
            memory: ~

        my_users:
            entity:
                class: App\Entity\MyUser
                property: username
```

