Задание:

необходимо разработать простейшую новостную ленту на PHP

1. Лента состоит из записей с параметрами: название, текст, время создания, время редактирования.
2. Нужна возможность просмотреть ленту новостей.
3. Нужна возможность добавлять, редактировать и удалять новости.
4. Небходимо реализовать возможность хранения данных как в MySQL, так и в обычных текстовых файлах.
Источник данных указывается в конфигурационном файле и может изменяться в процессе работы с приложением
(без миграции данных).

Особенности реализации:

движок упрощен до минимума во многих аспектах: отсутствуют проверки пользовательского ввода, "обрезана" диспетчеризация,
нет обработки многих потенциальных ошибок использования базовых классов и конфигурации, упрощена обработка ошибок и
исключений и для всех исключений используется один класс, отсутствует атомарность как в работе с базой новостей в файлах,
так и при синхронизации записи в несколько источников и др. 

в реализации многих классов уровень абстракции не выше, чем того требуют условия задачи. При неоходимости усложнять
функционал будет необходим рефакторниг.

движок не рассчитан на использование для каких-либо целей, кроме как ознакомление со стилем кода.