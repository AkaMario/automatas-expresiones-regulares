# 📘 Guía de Ejemplos y Expresiones Regulares - Verbo TO BE (Questions)

**Fundación Tecnológica Comfenalco de Cartagena**  
**Materia:** Autómatas, Gramáticas y Lenguajes  
**Proyecto:** Chatbot con Expresiones Regulares  
**Profesor:** Ing. Carlos García Castro  

---

## 🎯 1. Introducción

Este documento detalla la formulación de **Expresiones Regulares (Regex)** utilizadas por el chatbot para la validación estricta de estructuras interrogativas (*Questions*) en inglés utilizando el **verbo TO BE** en tiempo presente y pasado.

A continuación, se presenta **1 ejemplo detallado por cada tipo de pregunta** solicitado en el proyecto, junto con su estructura formal, expresión regular y análisis sintáctico.

---

## 📌 2. Estructuras y Ejemplos Detallados

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

### 🔹 2. Wh- Questions (Información)
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

### 🔹 3. Pasado (Was / Were)
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

## 📊 3. Tabla Resumen de Categorías y Ejemplos

| Tipo de Pregunta | Verbo / Auxiliar | Sujeto | Complemento | Oración Completa |
| :--- | :--- | :--- | :--- | :--- |
| **Yes/No (Presente)** | `Is` | `she` | `a nice girl` | `Is she a nice girl?` |
| **Wh- Question** | `Where` + `is` | `the cat` | *(vacío)* | `Where is the cat?` |
| **Pasado (Was/Were)** | `Were` | `you` | `a good student` | `Were you a good student?` |

---

## ⚠️ 4. Ejemplos de Frases Inválidas Detectadas por el Sistema

| Frase Ingresada | Motivo de Invalidez | Corrección Sugerida |
| :--- | :--- | :--- |
| `Is you a student?` | **Error de concordancia:** *you* requiere *Are*. | `Are you a student?` |
| `Where she is?` | **Error sintáctico:** El verbo To Be debe ir antes del sujeto. | `Where is she?` |
| `Was they in Barranquilla?` | **Error de concordancia:** *they* requiere *Were*. | `Were they in Barranquilla?` |
| `Am I a teacher` | **Error de puntuación:** Falta el signo de interrogación `?`. | `Am I a teacher?` |
