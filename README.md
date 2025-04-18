<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# 🚀 Laravel Pastebin Integration Project

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red?logo=laravel)](https://laravel.com)
[![Docker](https://img.shields.io/badge/Docker-Swarm-blue?logo=docker)](https://docs.docker.com/engine/swarm/)
[![PhpStorm](https://img.shields.io/badge/IDE-PhpStorm-purple?logo=jetbrains)](https://www.jetbrains.com/phpstorm/)

Проект на Laravel, взаимодействующий с [Pastebin.com](https://pastebin.com) через API. Реализует:

- 🔐 Авторизацию пользователя 
- 📝 Создание, удаление и просмотр паст
- 🛠 Административную панель на базе [Orchid Platform](https://orchid.software)
- 🐳 Развёртывание через Docker Swarm с безопасным хранением ключей в Docker Secrets

---

## 🧠 Как работает проект

```plaintext
[Laravel App] --> [Pastebin API]
       |
       +--> Авторизация через Яндекс OAuth
       |
       +--> Админка на Orchid
       |
       +--> MySQL (сохранение данных)
```

## 📦 Стек

- Laravel (PHP 8.2)
- Orchid Admin Panel
- Docker / Docker Compose
- MySQL 8.0
- Nginx
- PhpMyAdmin

## ⚙️ Требования

- Docker
- Docker Compose
- Docker Swarm 
- Git
- PHPStorm или любой другой редактор

## 🚀 Быстрый старт

## 1. 📁  Клонируйте репозиторий

```bash
git clone https://github.com/Alexsandra5235/paste.git
cd paste
```
## 2. Создайте .env и копируйте файл
```bash
cp .env.example .env
```

## 3. 🔐 Настройте Docker Secrets
Создай секреты:

```bash
echo "your-yandex-client-id" | docker secret create yandex_client_id -
echo "your-yandex-client-secret" | docker secret create yandex_client_secret -
echo "your-pastebin-api-key" | docker secret create pastebin_api_key -
```

## 4. Создание overlay-сеть (один раз)

```bash
docker network create --driver overlay laravel
```

## 5. Установка пакетного менеджера
```bash
npm install
```

## 6. Сборка образа

```bash
docker build -t my-laravel-app -f ./docker/php/Dockerfile .
```

## 7. 🚀 Развёртывание проекта в Docker Swarm
```bash
docker stack deploy -c docker-compose.yml laravel_project
```

## 🧱 Первоначальная настройка проекта
В контейнере app выполните следующие действия:

```bash
docker exec -it $(docker ps -qf "name=laravel_project_app") bash
```

Далее внутри контейнера:

```bash
composer install
php artisan migrate
```

```bash
php artisan key:generate
```

Создание администратора Orchid:

```bash
php artisan orchid:admin admin admin@admin.com password
```
Данная команда создат пользователя с логином admin@admin.com и паролем password.

Запустите обработчик очереди

```bash
php artisan queue:work
```

## Полезные команды
```bash

# Просмотр всех секретов
docker secret ls

# Просмотр всех сервисов
docker service ls 

# Остановка проекта:
docker stack rm laravel_project

# Очистка и кэширование конфигов:
php artisan config:clear
php artisan config:cache
```

## Сервисы должны выглядеть следующим образом:
```bash
NAME                         MODE         REPLICAS   IMAGE                          
laravel_project_app          replicated   1/1        my-laravel-app:latest
laravel_project_mysql        replicated   1/1        mysql:8.0                      
laravel_project_phpmyadmin   replicated   1/1        phpmyadmin/phpmyadmin:latest 
laravel_project_webserver    replicated   1/1        nginx:latest                  
```


## 🌐 Доступ к сервисам

- Приложение: [http://localhost:8000](http://localhost:8000)
- PhpMyAdmin: [http://localhost:8081](http://localhost:8081)
(логин: root, пароль: root)
