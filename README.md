<!-- <p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT). -->

# Instalacion del proyecto
**Se debe tener php 8.3 hacia arriba, nodejs, composer y postgres**
    Después de clonar el proyecto, ejecuta el siguiente comando en la terminal:

    composer install

instalaremos las dependencias con:

    npm i
    
crearemos el .env y generaremos la key con:

    php artisan key:generate

realizaremos las migraciones con:

    php artisan migrate
    
gitpor utlimo correremos el proyecto con:
****
    composer run dev



## Guía de Ejemplos y Expresiones Regulares - Verbo TO BE (Questions)

**Fundación Tecnológica Comfenalco de Cartagena**  
**Materia:** Autómatas, Gramáticas y Lenguajes  
**Proyecto:** Chatbot con Expresiones Regulares  
**Profesor:** Ing. Carlos García Castro  

---

## 1. Introducción

Este documento detalla la formulación de **Expresiones Regulares (Regex)** utilizadas por el chatbot para la validación estricta de estructuras interrogativas (*Questions*) en inglés utilizando el **verbo TO BE** en tiempo presente y pasado.

A continuación, se presenta **1 ejemplo detallado por cada tipo de pregunta** solicitado en el proyecto, junto con su estructura formal, expresión regular y análisis sintáctico.

---

## 2. Estructuras y Ejemplos Detallados

### 🔹 1. Yes/No Questions (Presente)
*Preguntas cerradas en presente simple cuya respuesta es Sí o No.*

* **Fórmula:**  
  $$\text{Verbo To Be (Am / Is / Are)} + \text{Sujeto} + \text{Complemento} + \text{?}$$

* **Ejemplo:**
  > **"Is she a nice girl?"** *(¿Es ella una chica agradable?)*

* **Desglose Sintáctico:**
  * **Verbo To Be:** `Is` (3ra persona del singular)
  * **Sujeto:** `she` (Pronombre personal singular)
  * **Complemento:** `a nice girl` (Frase nominal descriptiva)
  * **Signo de Cierre:** `?`

* **Expresión Regular (PCRE):**
  ```regex
  ^(?i)(Am\s+(?:I)\s+([a-z\s]+)\?|Is\s+(?:he|she|it|(?:the|this|that)\s+[a-z]+|[A-Z][a-z]+)\s+([a-z\s]+)\?|Are\s+(?:you|we|they|(?:the|these|those)\s+[a-z]+s?|[A-Z][a-z]+(?:\s+and\s+[A-Z][a-z]+)+)\s+([a-z\s]+)\?)$
  ```

---

### 2. Wh- Questions (Información)
*Preguntas abiertas que solicitan información específica mediante pronombres interrogativos (Wh- words).*

* **Fórmula:**  
  $$\text{Palabra Wh- (What/Where/When/Who/Why/How/Which)} + \text{Verbo To Be} + \text{Sujeto} + \text{Complemento (opcional/contextual)} + \text{?}$$

* **Ejemplo:**
  > **"Where is the cat?"** *(¿Dónde está el gato?)*

* **Desglose Sintáctico:**
  * **Palabra Wh-:** `Where` (Pronombre interrogativo de lugar)
  * **Verbo To Be:** `is` (Forma en presente singular)
  * **Sujeto:** `the cat` (Sustantivo común con artículo determinado)
  * **Complemento:** *(Opcional / Implícito por la pregunta de ubicación)*
  * **Signo de Cierre:** `?`

* **Expresión Regular (PCRE):**
  ```regex
  ^(?i)(What|Where|When|Who|Why|How|Which)\s+(am\s+(?:I)|is\s+(?:he|she|it|(?:the|this|that)\s+[a-z]+|[A-Z][a-z]+)|are\s+(?:you|we|they|(?:the|these|those)\s+[a-z]+s?|[A-Z][a-z]+(?:\s+and\s+[A-Z][a-z]+)+)|was\s+(?:I|he|she|it|(?:the|this|that)\s+[a-z]+|[A-Z][a-z]+)|were\s+(?:you|we|they|(?:the|these|those)\s+[a-z]+s?|[A-Z][a-z]+(?:\s+and\s+[A-Z][a-z]+)+))(?:\s+([a-z0-9\s,]+))?\?$
  ```

---

### 3. Pasado (Was / Were)
*Preguntas en tiempo pasado simple con el verbo TO BE.*

* **Fórmula:**  
  $$\text{Verbo To Be (Was / Were)} + \text{Sujeto} + \text{Complemento} + \text{?}$$

* **Ejemplo:**
  > **"Were you a good student?"** *(¿Fuiste un buen estudiante?)*

* **Desglose Sintáctico:**
  * **Verbo To Be:** `Were` (Forma en pasado para segunda persona / plural)
  * **Sujeto:** `you` (Pronombre personal)
  * **Complemento:** `a good student` (Frase nominal calificativa)
  * **Signo de Cierre:** `?`

* **Expresión Regular (PCRE):**
  ```regex
  ^(?i)(Was\s+(?:I|he|she|it|(?:the|this|that)\s+[a-z]+|[A-Z][a-z]+)\s+([a-z\s]+)\?|Were\s+(?:you|we|they|(?:the|these|those)\s+[a-z]+s?|[A-Z][a-z]+(?:\s+and\s+[A-Z][a-z]+)+)\s+([a-z\s]+)\?)$
  ```

---

##  3. Tabla Resumen de Categorías y Ejemplos

| Tipo de Pregunta | Verbo / Auxiliar | Sujeto | Complemento | Oración Completa |
| :--- | :--- | :--- | :--- | :--- |
| **Yes/No (Presente)** | `Is` | `she` | `a nice girl` | `Is she a nice girl?` |
| **Wh- Question** | `Where` + `is` | `the cat` | *(vacío)* | `Where is the cat?` |
| **Pasado (Was/Were)** | `Were` | `you` | `a good student` | `Were you a good student?` |

---

## 4. Ejemplos de Frases Inválidas Detectadas por el Sistema

| Frase Ingresada | Motivo de Invalidez | Corrección Sugerida |
| :--- | :--- | :--- |
| `Is you a student?` | **Error de concordancia:** *you* requiere *Are*. | `Are you a student?` |
| `Where she is?` | **Error sintáctico:** El verbo To Be debe ir antes del sujeto. | `Where is she?` |
| `Was they in Barranquilla?` | **Error de concordancia:** *they* requiere *Were*. | `Were they in Barranquilla?` |
| `Am I a teacher` | **Error de puntuación:** Falta el signo de interrogación `?`. | `Am I a teacher?` |
