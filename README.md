# Noticias API – CakePHP Backend

[![Total Downloads](https://img.shields.io/packagist/dt/cakephp/app.svg?style=flat-square)](https://packagist.org/packages/cakephp/app)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%208-brightgreen.svg?style=flat-square)](https://github.com/phpstan/phpstan)

Este proyecto es un backend desacoplado desarrollado con [CakePHP 5](https://cakephp.org), diseñado como una **API RESTful** para la **gestión de noticias**, y pensado para ser consumido por un frontend separado (como Vue.js, React, Angular, etc.).

---

## 🚀 Características

- API JSON basada en CakePHP 5.x.
- CRUD completo de noticias.
- Preparado para integración con frontend desacoplado.
- CORS habilitado.
- Middleware para peticiones JSON (`BodyParserMiddleware`).
- Código limpio y modular.

---

## 🧰 Requisitos

- PHP 8.1 o superior
- Composer
- MySQL/MariaDB u otra base de datos compatible
- Servidor con soporte para URL rewriting (Apache, Nginx)

---

## ⚙️ Instalación

Clona el repositorio y configura el entorno:

```bash
git clone https://github.com/EnriqueJimenezMartinez/formula-api.git noticias api
cd noticias-api
composer install
cp config/app_local.example.php config/app_local.php
