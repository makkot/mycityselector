My City Selector Joomla Extension
=================================

|       Joomla | 2.5 - 3.x       |
|--------------|-----------------|
| Package      | module & plugin |
| Version      | 1.2.8 beta      |

## Общие сведения

My City Selector - это расширение для CMS Joomla, позволяющее администратору сайта отображать разную информацию для разных городов (список которых он может определить в настройках).

<img src="https://raw.githubusercontent.com/adamasantares/mycityselector/doc128/doc_images/intor.png" alt="" width="100%" />

Изначально, когда расширение создавалось, преследовалась цель сделать возможным изменение части контента страницы при переключении города. То есть домен (хост) при этом не менялся. В виду чего возникла проблема с индексированием, ведь поисковики не знали о разном контенте. Для них это были одни и те же страницы. Поэтому в последствии была добавлена возможность разделить города на адресном уровне. Сначала была попытка разделения на подуровни вида "/moscow/page/subpage" или "/spb/page/subpage". В принципе решение сносное, но были некоторые сложности. Далее было решено, что оптимальный вариант - это наличие для каждого города своего поддомена (точнее по факту, сайт существует в единственном числе и все субдомены ссылаются на основной домен). Такое разделение оказалось оптимальным и рекомендуется к применению (можно использовать и предыдущие спобобы, если вам их вполне достаточно). Так или иначе, в настройках задается город по умолчанию, так что в любом случае с индексацией не должно возникнуть больших проблем.

В добавок хочется отметить, что расширение совместимо с модулем мультиязычности. Но при этом может понадобиться создать для каждого языка свою копию модуля mod_mycityselector. На данный момент, есть только один серьезный недостаток - это невозможность сопоставить между собой один и тот же город с разными названиями. Поэтому при переключении языка, скорее всего потребуется повторно выбрать город.
Данный недочет обязательно будет исправлен в следующей версии.

*Примечание: все изображения приводимые здесь основаны на версии Joomla 3.3.6*

## Как это работает?

Принцип работы относительно прост. Расширение оперирует специальными тегами вида [city Москва][/city], внутри которых должна содержаться информация для соответствующего города.
```
Телефон: [city Москва]+7 499 888-77-66[/city][city Владивосток]+7 423 55-66-77[/city]
```
На одной странице таких тегов может быть много, а на самой странице отображается информация только из тех тегов, которые соответствуют текущему выбранному городу.
Действие тегов не ограничивается только контентом материалов, вы можете использовать их даже в файлах шаблона. Так например, можно для разных городов отображаться разные позиции модулей.
```php
<div class="some-block">
  [city Москва]<jdoc:include type="modules" name="position-msk" style="none" />[/city]
  [city *]<jdoc:include type="modules" name="position-all" style="none" />[/city]
</div>
```
Таким образом "position-msk" со всеми своими модулями будет отображаться только для Москвы, а вторая позиция - для всех остальных городов.

Рассмотрим подробнее все возможные варианты записи тегов:

 1. Один город ```[city Омск]```
 2. Несколько городов ```[city Москва,Санкт-Петербург]```
 3. Все города ```[city *]``` - точнее любые кроме тех, что указаны явно в других тегах текущей страницы
 4. Любой город кроме указанного (отрицание) ```[city !Новосибирск]```
 5. Любой город кроме указанных ```[city !Омск,Владивосток]```

Вот в общем-то и вся хитрость.

*Примечание: используя эти теги в редакторе материалов, старайтесь не делать отступов и переносов между закрывающим тегом одного города и открывающим другого, поскольку могут появляться нежелательные отступы или переносы срок (редактор автоматически корректирует html структуру текста и может вставлять между тегами лишние теги).*


## Установка

На данный момент установка возможна только по ссылке или из архива [v1.2.8-beta.2](https://github.com/adamasantares/mycityselector/releases/tag/v1.2.8-beta.2).

В ближайшем будущем планируется разместить на [JED](http://extensions.joomla.org/).

## Настройка

Расширение состоит из плагина и модуля. При корректной установке плагин должен активироваться автоматически. Так что сразу переходим к настройкам модуля. Важные параметры модуля находятся не только на основной вкладке настроек, но и на вкладке дополнительных параметров.

<img src="https://raw.githubusercontent.com/adamasantares/mycityselector/doc128/doc_images/mod_base_settings.png" alt="" width="100%" />

<img src="https://raw.githubusercontent.com/adamasantares/mycityselector/doc128/doc_images/mod_advanced_settings.png" alt="" width="100%" />

Рассмотрим все параметры по порядку.

### Основной домен

Необходим для корректного формирования адресов поддоменов.
Автоматическое определение основного домена (в случае, когда вы уже на поддомене) работает только при условии, что у сайта домен второго уровня (site.ru). Если же сайт находится на одном из многочисленных доменов третьего уровня (site.com.ru), то возникает проблема. Вообще, этот параметр устанавливается автоматически при инсталляции расширения (в качестве значения берется имя текущего хоста).

### Выбор города при входе

Если данный параметр включен, то при первом входе пользователя на сайт, модуль стразу предложит ему выбрать город из списка.
*Примечание: если параметр включен, то не будет работать yandex-geolocation, так как происходит конфликт между автоматическим определением города и выбором пользователя.*

### Геолокация

Функция автоматического определения города. Если автоматически определенный город есть в спике (настройках модуля), то происходит автоматическое переключение контента или редирект на соответствующий субдомен.
Доступны следующие варианты:

 * SypexGeo - определение города по IP с помощью соответствующего сервиса
 * Yandex Geo - определение города посредством связки механизма геолокации браузера и определения города по координатам через сервис Yandex geocode-maps
 * связка SypexGeo + YandexGeo, когда первый используется как более быстрого определения, а второй, вроде контрольного выстрела

*Примечание: использование YandexGeo вероятнее всего выдаст пользователю запрос на разрешение использовать функционал геолокации, имейте это в виду.*
<img src="https://raw.githubusercontent.com/adamasantares/mycityselector/doc128/doc_images/geolocation_request.png" alt="" />

По умолчанию данный функционал отключен.

### Список городов

Представляет из себя таблицу состоящую из четырех столбцов:

 - город по умолчанию (какой город будет отображен, если его не удастся определить автоматически)
 - название города или группы городов
 - название поддомена или URL-префикс для страниц (например "/moscow/" или "/spb/")
 - операции "удалить строку" и "переместить строку"

Рассмотрим на примерах, для начала на простом списке без групп и адресов.

<img src="https://raw.githubusercontent.com/adamasantares/mycityselector/doc128/doc_images/example_1.png" alt="" />

При такой конфигурации, контент для всех городов существует в рамках одного домена. То есть при индексации вашего сайта, скорее всего роботы получат контент для города, установленного по умолчанию. Для пользователя, переключение контента будет происходить "на лету", без каких либо перезагрузок страниц.

Теперь, если назначить каждому городу свою страницу на сайте, то при выборе города будет происходить redirect на соответствущую страницу.

<img src="https://raw.githubusercontent.com/adamasantares/mycityselector/doc128/doc_images/example_2.png" alt="" />

Обратите внимание, что основному городу страница не задается, поскольку не может у сайта отсутствовать главная страница (корень сайта). Таким образом главная страница сайта - это главная страница города, указанного по умолчанию. Для лучшего понимания, изображу как должна выглядеть структура такого сайта.

```
/  (Главная страница для Москвы)
/about-company  (О компании)
/services  (Услуги)
/contact  (Контакты)
/products  (Продукция)
/spb/ (Главная страница для Санкт-Петербурга)
  /spb/about-company
  /spb/services
  /spb/contact
  /spb/products
/nsk/ (Главная страница для Новосибирска)
  /nsk/about-company
  /nsk/services
  /nsk/contact
  /nsk/products
 и т.д.
```
Глядя на такую структуру можно подумать, что вам придется создавать дубликаты каждой страницы из главного города, но это не так. На самом деле достаточно создать страницы только для главного города, а страницы для других городов - это все те же страницы главного города, но с другими url.
Дело в том, что структура сайта задается через компонент "Меню", следовательно достаточно для каждого города сделать свое меню, но назначить для адресов страницы свои префиксы (/about-company -> /spb/about-company).
То есть в корне города Санкт-Петербург (/spb/) должно отображаться меню, копирующее меню главного города, но с префиксами у адресов.
Конечно такой подход не избавит от необходимости вручную создавать копии записей, но вместо копирования материалов, нужно будет создавать копии пунктов меню. Но в то же время удобно управлять контентом, так как информация для всех городов находится в одном материале для каждой страницы.

...


## Кастомизация

...
