# DaSSSH Modules

**DaSSSH** is a project which translates all core front-end technologies (HTML5, CSS3, Javascript and SVG) into **JSON**.

**DaSSSH** stands for **Data and Scripts, Styles, SVG & HTML**.

**DaSSSH Modules** represent a core **Ashiva** Component.

**Ashiva Modules** *are* **DaSSSH Modules**. Each contain (in a single block of JSON) one, several or all of the following:

1) *Data*
2) *Scripts (**Javascript** or **PHP**)*
3) *Styles (**CSS**)*
4) ***SVG** Vectors*
5) ***HTML** Markup*

Other technologies can be used to write Markup, Styles and Scripts.

**eg.**

1) Scripts can be written in *Typescript*, *Svelte*, *jQuery* etc.
2) Styles can be written in *Sass*, *Less*, *Stylus* etc.
3) Markup can be written in *Markdown*, *HAML*, *Emmet*, *Pug* etc.

**However**, these other languages will ALWAYS be parsed into Javascript, CSS and HTML before being serialized as JSON.
