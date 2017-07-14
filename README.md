Elusive-Framework
=================
The Elusive Framework is a minimalist PHP development framework for creating websites and web apps.

### Documentation
+ Installation Documentation - _coming soon_
+ [Code Documentation](http://rawgit.com/ElusiveConcepts/Elusive-Framework/master/docs/index.html)

#### Include the Framework as a Subtree
If you want to include the core framework files as a git subtree, so you can pull down changes directly into your project, use the following commands:

__Add Elusive Core Files as Git Subtree:__
```bash
# add the Elusive remote and create a tracking branch
$ git remote add -f elusive-origin https://github.com/ElusiveConcepts/Elusive-Framework.git
$ git checkout -b elusive/framework elusive-origin/master

# split the 'elusive' subdirectory into separate elusive-core branch
git subtree split -q --squash --prefix=src/elusive --annotate="[elusive]" --rejoin -b elusive/core

# add the core branch as a subdirectory to your working <branch>
git checkout <branch>
git subtree add --prefix=<path>/elusive --squash elusive/core

# add the necessary application files
# if necessary use git mv to change src/ to <path>/
git checkout elusive/framework src/config.php
git checkout elusive/framework src/app/app.php
git checkout elusive/framework src/app/controllers/Primary.class.php
git checkout elusive/framework src/webroot/index.php
git checkout elusive/framework src/webroot/.htaccess
```

__Update Elusive Core Files:__
```bash
# checkout the tracking branch, fetch & rebase.
git checkout elusive/framework
git pull elusive-origin

# update the elusive-core branch with changes from elusive-origin
git subtree split -q --prefix=src/elusive --annotate="[elusive] " --rejoin -b elusive/core

# switch to working branch and subtree merge to update the framework files
git checkout <branch>
git subtree merge -q --prefix=<path>/elusive --squash elusive/core
```

---

### Build Process:

$ npm install

$ grunt build

---

_Copyright (c) 2011-2017 Elusive Concepts, LLC._

![Elusive Concepts, LLC.](https://elusive-concepts.com/images/ui/ec_logo.png "Elusive Concepts, LLC.")
