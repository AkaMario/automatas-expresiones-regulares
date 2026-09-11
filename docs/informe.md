# Informe del proyecto: RegularBot, chatbot con expresiones regulares para preguntas Yes/No con TO BE en presente

**Asignatura:** Autómatas, Gramáticas y Lenguajes.  
**Proyecto:** Chatbot educativo para validar preguntas Yes/No en inglés.  
**Alcance del informe:** preguntas cerradas con el verbo TO BE en presente simple: `am`, `is` y `are`.

## 2. Introducción

RegularBot es una aplicación web educativa que permite practicar la construcción de **preguntas Yes/No en inglés con el verbo TO BE en presente simple**. Estas preguntas admiten respuestas afirmativas o negativas y se construyen con `am`, `is` o `are`. El usuario ingresa su nombre, escribe una oración y recibe una respuesta que indica si esta coincide con el patrón admitido. La aplicación conserva las conversaciones y permite consultar el historial, crear nuevos chats, cambiar sus títulos y eliminarlos.

El núcleo del proyecto es un validador basado en **expresiones regulares**. Estas permiten convertir reglas de escritura en patrones computacionales que reconocen el orden del verbo y del sujeto, ciertas relaciones de concordancia, los caracteres permitidos en el complemento y el signo de interrogación final. Además de aceptar o rechazar entradas, el servicio identifica componentes de la oración y genera diagnósticos para algunos errores frecuentes.

La importancia de las expresiones regulares radica en que conectan los conceptos de lenguajes formales con una aplicación práctica. Una estructura como `Is she a nice girl?` se puede representar mediante reglas verificables, facilitando la automatización de ejercicios y la explicación de sus resultados.

El informe se centra exclusivamente en la categoría `YES_NO_PRESENT`. Su propósito es explicar cómo las expresiones regulares permiten reconocer la estructura `Am / Is / Are + sujeto + complemento + ?` y apoyar la práctica de estas preguntas.

## 3. Marco teórico

### 3.1. Expresiones regulares

Una expresión regular es una notación que describe patrones de cadenas de caracteres. En la teoría de lenguajes formales, permite representar lenguajes regulares mediante operaciones como unión, concatenación y repetición. Estos lenguajes pueden reconocerse mediante autómatas finitos.

En programación, las expresiones regulares se utilizan para buscar coincidencias, validar formatos, extraer información y reemplazar fragmentos de texto. El proyecto emplea las funciones `preg_match()`, `preg_replace()` y `preg_split()` de PHP.

#### Operadores y elementos básicos

| Elemento | Función | Ejemplo |
| --- | --- | --- |
| Concatenación | Exige elementos consecutivos. | `Is` reconoce esa secuencia de letras. |
| `\|` | Presenta alternativas. | `am\|is\|are` admite una de las tres formas. |
| `(...)` | Agrupa y captura una coincidencia. | `(is\|are)` captura el verbo. |
| `(?:...)` | Agrupa sin capturar. | `(?:he\|she\|it)` reúne sujetos posibles. |
| `*` | Repite cero o más veces. | `a*` admite una cadena vacía o varias letras `a`. |
| `+` | Repite una o más veces. | `[a-z]+` exige al menos una letra. |
| `?` | Hace opcional el elemento anterior. | `s?` permite una `s` o su ausencia. |
| `{n,m}` | Limita la cantidad de repeticiones. | `[0-9]{1,3}` admite entre uno y tres dígitos. |
| `[...]` | Define un conjunto de caracteres. | `[a-z]` admite una letra del rango indicado. |
| `^` y `$` | Delimitan el inicio y el final de la coincidencia. | `^Is.*\?$` busca una estructura que comience con `Is` y termine en `?`. |
| `.` | Admite cualquier carácter, normalmente salvo salto de línea. | `a.b` coincide con `acb`. |
| `\s` | Representa un carácter de espacio en blanco. | `\s+` reconoce uno o más espacios en blanco. |
| `\?` | Representa un signo de interrogación literal. | `\?$` exige el signo al final. |

PHP utiliza sintaxis PCRE, que también ofrece capturas con nombre, como `(?P<verb_is>Is)`, y aserciones negativas: `(?!...)` comprueba que no siga un patrón y `(?<!...)` que no lo preceda. El modificador `i`, colocado después del delimitador final, permite comparar sin distinguir mayúsculas de minúsculas. Estas construcciones se describen en el [manual oficial de sintaxis PCRE de PHP](https://www.php.net/manual/en/reference.pcre.pattern.syntax.php).

#### Ejemplo didáctico

```regex
/^Is\s+(?:he|she|it)\s+[a-z]+\?$/i
```

Este patrón reconoce una pregunta formada por `Is`, un pronombre singular, un complemento de una palabra y `?`. Acepta `Is she happy?` y rechaza `Is you happy?` o `Is she happy`. Es una simplificación explicativa; los patrones implementados incluyen más sujetos y complementos.

### 3.2. Chatbots y aplicación en el proyecto

Un chatbot es un programa que interactúa con una persona mediante mensajes y responde según una lógica definida. Puede organizarse mediante reglas explícitas o incorporar modelos de inteligencia artificial.

RegularBot es un **chatbot basado en reglas**: la conversación sigue estados definidos y la validación depende de expresiones regulares. Su implementación no utiliza un modelo generativo para producir las respuestas. El flujo solicita el nombre, recibe una pregunta, comunica el resultado y consulta si el usuario desea continuar. Al finalizar, presenta un resumen de preguntas evaluadas, válidas e inválidas.

Su aplicación educativa consiste en permitir la práctica repetida de preguntas Yes/No con TO BE en presente y relacionar los resultados con reglas sintácticas. El servicio devuelve información detallada, como componentes y sugerencias; la interfaz actual presenta principalmente un mensaje breve de aceptación o rechazo.

### 3.3. Gramática de las preguntas Yes/No con TO BE en presente

El verbo **TO BE** puede expresar identidad, estado o ubicación y suele corresponder a «ser» o «estar» en español. Para formular preguntas Yes/No en presente simple se coloca `am`, `is` o `are` antes del sujeto, sin utilizar los auxiliares `do` o `does`. La concordancia es la siguiente, de acuerdo con la [guía del verbo *be* del British Council](https://learnenglish.britishcouncil.org/free-resources/grammar/english-grammar-reference/verb-be).

| Sujeto | TO BE en presente |
| --- | --- |
| `I` | `am` |
| `he`, `she`, `it` | `is` |
| `you`, `we`, `they` | `are` |
| Sujeto nominal singular, como `the cat` o `Maria` | `is` |
| Sujeto nominal plural, como `the boys` o `Michael and Charles` | `are` |

#### Preguntas cerradas en presente

```text
Am / Is / Are + sujeto concordante + complemento + ?
```

Ejemplos del proyecto: `Am I a teacher?`, `Is she a nice girl?` y `Are the boys happy?`.

#### Ejemplos y respuestas cortas

| Pregunta | Respuesta afirmativa | Respuesta negativa |
| --- | --- | --- |
| `Is she a teacher?` | `Yes, she is.` | `No, she isn't.` |
| `Are you a student?` | `Yes, I am.` | `No, I'm not.` |
| `Are the boys happy?` | `Yes, they are.` | `No, they aren't.` |

Estas respuestas ilustran por qué se denominan preguntas Yes/No. El validador analiza la estructura de la pregunta escrita por el usuario; las respuestas cortas de la tabla se incluyen como explicación gramatical.

#### Representación de las reglas del proyecto

La siguiente notación resume las alternativas reconocidas; no pretende describir toda la gramática inglesa:

```text
PreguntaPresente → "Am" espacio "I" espacio Complemento "?"
                  | "Is" espacio SujetoSingular espacio Complemento "?"
                  | "Are" espacio SujetoPlural espacio Complemento "?"
```

Los sujetos y complementos concretos se restringen mediante los patrones de la implementación. Una coincidencia demuestra pertenencia al formato programado; no garantiza que el contenido tenga sentido ni que abarque todas las construcciones correctas del inglés.

## 4. Sustentación del lenguaje de programación seleccionado

El lenguaje principal es **PHP**, utilizado con **Laravel** para implementar el servidor. En el entorno revisado se verificaron PHP **8.5.0**, Laravel **13.29.0** y PHPUnit **12.5.34**. Estas versiones corresponden al entorno instalado; `composer.json` declara rangos de compatibilidad.

La elección de PHP se sustenta en los siguientes aspectos:

1. **Procesamiento de texto integrado.** Las funciones `preg_match()`, `preg_replace()` y `preg_split()` permiten reconocer patrones, normalizar entradas y separar palabras con las herramientas ya utilizadas por el proyecto.
2. **Extracción de componentes.** Las capturas con nombre facilitan recuperar el verbo, el sujeto y el complemento para construir una respuesta explicativa.
3. **Integración con una aplicación web.** Laravel organiza las rutas, la validación de solicitudes, las sesiones y las respuestas JSON que conectan el navegador con el validador.
4. **Persistencia organizada.** Los modelos `Language`, `Conversation` y `ConversationMessage` separan las definiciones de patrones, las conversaciones y sus mensajes. Las migraciones describen su estructura de almacenamiento.
5. **Mantenimiento y comprobación.** La separación entre controlador y servicio permite revisar las reglas de reconocimiento sin mezclar toda la lógica con la interfaz. PHPUnit permite comprobar ejemplos y operaciones HTTP.

Como complemento, **JavaScript** gestiona la interacción en el navegador y envía las solicitudes mediante `fetch()`. Las vistas **Blade** construyen la página, mientras **Tailwind CSS** y **Vite** forman parte de las herramientas de presentación y compilación declaradas en `package.json`.

La selección resulta adecuada para este alcance porque reúne validación textual, interacción web y almacenamiento en una estructura que el proyecto ya implementa.

## 5. Implementación

### 5.1. Listado de las expresiones regulares

La definición de `YES_NO_PRESENT` está en [`database/seeders/LanguageSeeder.php`](../database/seeders/LanguageSeeder.php). El seeder la registra en la tabla `languages`, y el servicio consulta los registros activos cuyo código está habilitado. A continuación se documentan el patrón de preguntas Yes/No en presente y las expresiones auxiliares que apoyan su procesamiento.

#### 5.1.1. Subpatrones de sujetos

El siguiente fragmento reproduce los dos subpatrones de sujetos que el seeder utiliza para construir la expresión de preguntas Yes/No en presente. El pronombre `I` se incluye directamente en la rama de `Am`:

```php
$singularSubject = '(?:he|she|it|this\s+[a-z]+|that\s+[a-z]+|the\s+[a-z]+(?<!s)|(?!(?:you|we|they|he|she|it|i|the|this|that|these|those)\b)[a-z]+)';
$pluralSubject = '(?:you|we|they|these\s+[a-z]+|those\s+[a-z]+|the\s+[a-z]+s|(?!(?:you|we|they|he|she|it|i|the|this|that|these|those)\b)[a-z]+(?:\s+and\s+(?!(?:you|we|they|he|she|it|i|the|this|that|these|those)\b)[a-z]+)+)';
```

Las alternativas incluyen pronombres, grupos encabezados por `the` y demostrativos, y palabras tratadas como nombres. La construcción con `and` admite sujetos coordinados. La aserción `(?!...\b)` evita que las palabras enumeradas se reconozcan por la alternativa genérica destinada a nombres.

Para los grupos con `the`, la terminación en `s` aproxima el plural, y `(?<!s)` restringe la alternativa singular. Es una simplificación: no resuelve plurales irregulares ni todos los sustantivos singulares terminados en `s`.

#### 5.1.2. Preguntas cerradas en presente: `YES_NO_PRESENT`

Expresión construida en PHP con los subpatrones anteriores:

```php
'/^(?:(?P<verb_am>Am)\s+(?P<subject_i>I)|(?P<verb_is>Is)\s+(?P<subject_singular>'.$singularSubject.')|(?P<verb_are>Are)\s+(?P<subject_plural>'.$pluralSubject.'))\s+(?P<complement>[a-zA-Z0-9\s,\'.-]+)\?$/i'
```

Sus tres ramas relacionan `Am` con `I`, `Is` con el sujeto singular y `Are` con el sujeto plural. Después exigen un complemento y `?`. Las capturas `verb_am`, `verb_is`, `verb_are`, `subject_i`, `subject_singular`, `subject_plural` y `complement` permiten recuperar las partes reconocidas.

**Ejemplo:** `Is she a nice girl?` produce el verbo `Is`, el sujeto `she` y el complemento `a nice girl`.

#### 5.1.3. Expresiones auxiliares del validador

Estas expresiones aparecen en [`SentenceValidatorService.php`](../app/Services/SentenceValidatorService.php):

| Expresión | Uso |
| --- | --- |
| `/\s+/` | Reduce espacios en blanco consecutivos a un espacio mediante `preg_replace()` y separa palabras mediante `preg_split()`. |
| `/^(this\|that\|these\|those)\s+/i` | Identifica sujetos que empiezan por un demostrativo. |
| `/^the\s+/i` | Identifica grupos nominales encabezados por `the`. |
| `/^[A-Z][a-z]+(?:\s+and\s+[A-Z][a-z]+)*$/` | Clasifica nombres con inicial mayúscula, incluidos nombres coordinados. |
| `/^[A-Z][a-z]+$/` | Reconoce una primera palabra con formato de nombre durante el diagnóstico de estructuras afirmativas. |
| `'/^'.preg_quote($verb, '/').'/i'` | Construye un patrón para reemplazar el verbo inicial en sugerencias de concordancia; por ejemplo, `/^Is/i`. |

La etiqueta que devuelve el programa para `this pencil` es «Pronombre Demostrativo». En esa construcción, `this` funciona gramaticalmente como determinante demostrativo del sustantivo; la etiqueta del código es una simplificación de presentación.

La comprobación del signo final también utiliza `str_ends_with()`, y varias decisiones usan listas de palabras con `in_array()`. Por ello, el diagnóstico combina expresiones regulares con condiciones programadas.

#### 5.1.4. Expresiones auxiliares de la interfaz

La función `escapeHtml()` de [`resources/js/chatbot.js`](../resources/js/chatbot.js) utiliza `/&/g`, `/</g`, `/>/g`, `/"/g` y `/'/g` para reemplazar esos caracteres por entidades HTML. El modificador `g` aplica el reemplazo a todas las coincidencias. Estos patrones sirven para presentar texto en HTML; no validan la gramática de las preguntas.

### 5.2. Módulo del chatbot

Los archivos principales son [`ChatbotController.php`](../app/Http/Controllers/ChatbotController.php), [`chatbot.js`](../resources/js/chatbot.js), [`inicio.blade.php`](../resources/views/inicio.blade.php) y los componentes de `resources/views/components/chat/`.

El controlador recibe los mensajes, comprueba los datos de la solicitud, invoca el servicio y devuelve JSON. El mensaje debe ser una cadena de hasta 500 caracteres. También identifica la conversación de la sesión, guarda el resultado y calcula las estadísticas correspondientes.

JavaScript controla las etapas `name`, `sentence`, `continue` y `ended`. Envía la pregunta mediante `fetch()`, muestra el resultado y permite continuar con respuestas como `yes`, `si` o `sí`, o finalizar con `no`. Además, gestiona las acciones del historial.

| Ruta y método | Responsabilidad |
| --- | --- |
| `GET /` | Presentar la página del chatbot. |
| `POST /api/validate` | Validar una oración y registrar su resultado. |
| `GET /api/examples` | Consultar ejemplos, entre ellos los de `YES_NO_PRESENT` utilizados en esta práctica. |
| `GET /api/conversation/history` | Recuperar el historial de la sesión. |
| `POST /api/conversations` | Crear una conversación. |
| `PATCH /api/conversations/{conversation}` | Cambiar el título de una conversación de la sesión. |
| `DELETE /api/conversations/{conversation}` | Eliminar una conversación de la sesión. |

Los modelos `Language`, `Conversation` y `ConversationMessage` conservan los patrones, las conversaciones y los resultados. Cada mensaje puede guardar la oración original, la respuesta del bot, su validez, la categoría, el error, la sugerencia y el resultado detallado de validación.

### 5.3. Módulo validador

`SentenceValidatorService` concentra el reconocimiento y el diagnóstico. Para la validación de preguntas Yes/No en presente, su proceso es el siguiente:

1. **Cargar reglas:** obtener la definición activa de `YES_NO_PRESENT` a partir del catálogo de patrones habilitados.
2. **Preparar la entrada:** eliminar espacios exteriores con `trim()`, detectar una entrada vacía y normalizar espacios interiores.
3. **Comparar:** ejecutar `preg_match()` con el patrón de `YES_NO_PRESENT` cuando se selecciona esta categoría.
4. **Extraer:** si hay coincidencia, recuperar la forma `am`, `is` o `are`, el sujeto y el complemento, y clasificar el sujeto.
5. **Diagnosticar:** si no hay coincidencia, buscar errores frecuentes y preparar una explicación o sugerencia.
6. **Responder:** devolver un arreglo con `is_valid`, categoría y datos de análisis, que el controlador incorpora a la respuesta JSON.

Entre los diagnósticos relevantes para esta práctica se encuentran `EMPTY_INPUT`, `MISSING_QUESTION_MARK`, `AFFIRMATIVE_INSTEAD_OF_QUESTION`, `SUBJECT_VERB_DISAGREEMENT` y `SYNTAX_OR_VOCABULARY_ERROR`.

| Entrada | Diagnóstico previsto por la lógica |
| --- | --- |
| `Is you a student?` | Falta de concordancia; sugiere `Are you a student?`. |
| `Am I a teacher` | Falta `?`; sugiere `Am I a teacher?`. |
| `She is a teacher?` | Orden afirmativo; sugiere `Is she a teacher?`. |
| `Are she happy?` | Falta de concordancia; sugiere `Is she happy?`. |

#### Alcances y limitaciones

El reconocimiento aproxima una parte de la sintaxis inglesa. Los patrones permiten letras ASCII, números y ciertos signos en el complemento, pero no comprueban su significado, su estructura interna ni su vocabulario. Asimismo, las alternativas genéricas de nombres no verifican que una palabra sea realmente un nombre propio, y la distinción de número mediante la terminación `s` es limitada.

Los patrones cerrados exigen un complemento según las reglas del proyecto, aunque en contextos reales puedan existir preguntas elípticas. Tampoco se cubren todas las variantes de sujetos, contracciones o preguntas negativas. Estas restricciones deben considerarse al interpretar la etiqueta «válida»: indica coincidencia con las reglas implementadas.

### 5.4. Módulo de pruebas

El proyecto utiliza **PHPUnit**. Las comprobaciones relacionadas con las preguntas Yes/No en presente y el funcionamiento del chatbot se encuentran en los siguientes archivos:

| Archivo | Comprobaciones relevantes para el informe |
| --- | --- |
| [`tests/Feature/SentenceValidatorTest.php`](../tests/Feature/SentenceValidatorTest.php) | Ejemplos válidos de presente; diagnósticos de entradas inválidas; API de validación; persistencia, estadísticas e historial; creación, cambio de título y eliminación de chats; rechazo de una actualización desde otra sesión. |
| [`tests/Feature/ChatbotPageTest.php`](../tests/Feature/ChatbotPageTest.php) | Respuesta de la página, saludo de RegularBot y elementos del espacio de trabajo. |

Las pruebas utilizan `RefreshDatabase` y cargan `LanguageSeeder` cuando requieren las definiciones. En `phpunit.xml` se configura PostgreSQL con la base `automatas_testing`. La prueba de página inspecciona la respuesta HTML; no ejecuta la interacción JavaScript en un navegador.

El caso `test_yes_no_present_valid_sentences` comprueba preguntas como `Am I a teacher?`, `Is the cat brown?`, `Are the boys happy?` y `Are Michael and Charles doctors?`. El caso `test_invalid_sentences_give_feedback` comprueba errores de concordancia, ausencia del signo de interrogación y uso de una estructura afirmativa.

Comando para ejecutar los dos archivos:

```bash
php artisan test --compact tests/Feature/SentenceValidatorTest.php tests/Feature/ChatbotPageTest.php
```

**Resultado de la verificación durante la elaboración del informe:** se intentaron ejecutar 14 pruebas, pero todas finalizaron con error de conexión a PostgreSQL (`SQLSTATE[08006]`) en `127.0.0.1:5432`, antes de realizar aserciones. Por tanto, esta ejecución no permite afirmar que las pruebas hayan pasado ni evaluar el comportamiento funcional. Su comprobación queda pendiente de disponer de conexión a la base de pruebas configurada.

## 6. Conclusiones

### 6.1. Aprendizajes sobre expresiones regulares

El proyecto muestra cómo transformar estructuras lingüísticas delimitadas en reglas computacionales. La alternancia permite representar diferentes combinaciones de verbo y sujeto; los cuantificadores expresan repeticiones; las anclas delimitan la entrada; y las capturas con nombre permiten recuperar información útil para explicar una coincidencia.

Otro aprendizaje es la conveniencia de construir patrones a partir de fragmentos reutilizables. Separar sujetos singulares y plurales reduce la repetición conceptual y facilita relacionar cada rama con la forma verbal correspondiente.

También se evidencia la necesidad de distinguir entre coincidencia sintáctica y corrección lingüística completa. Las expresiones regulares son útiles para formatos acotados, pero las excepciones gramaticales, el vocabulario y el significado requieren un análisis adicional. Los ejemplos positivos deben complementarse con casos negativos y límites para detectar aceptaciones o rechazos inesperados.

### 6.2. Enriquecimiento personal, laboral y académico

En el ámbito **personal**, el ejercicio promueve el razonamiento lógico, la atención al detalle y la revisión crítica de resultados. Un cambio pequeño en un patrón puede modificar las cadenas aceptadas, lo que exige precisión al formular y explicar las reglas.

En el ámbito **laboral**, el proyecto permite ejercitar competencias aplicables a la validación de formularios, el procesamiento de texto y el desarrollo de aplicaciones web. La organización en módulos, la persistencia de datos y las pruebas automatizadas son prácticas transferibles al mantenimiento de software.

En el ámbito **académico**, la implementación relaciona expresiones regulares, lenguajes formales y reconocimiento de cadenas con una herramienta de práctica del inglés. Además, permite comprender que una gramática implementada representa un alcance específico y debe documentar sus restricciones.

Como posibilidades de mejora se identifican ampliar la cobertura de casos límite de preguntas Yes/No en presente, revisar la clasificación de sujetos, hacer más visible la retroalimentación detallada y completar las comprobaciones automatizadas. Estas acciones permitirían evaluar con mayor precisión el alcance educativo y técnico del sistema.
