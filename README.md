# REST API для работы с пользователями
### Настройка базы данных
Для корректной работы нужно создать бд и таблицу в ней, для этого выполните код на sql:
```sql
CREATE DATABASE CRUDA;

USE CRUDA;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
);
```

## Методы

### 1. Создание пользователя
- **Метод:** POST
- **URL:** /api.php
- **Тело запроса:**
```json
{
"login": "username",
  "password": "password123",
  "email": "user@example.com"
}
```

- **Ответ:** 
```json
{"message": "User created successfully"}
```

![111](https://github.com/user-attachments/assets/4dafd5cd-f16a-46f5-a73d-9f4038f65b74)

### 2. Получить информацию о пользователе
- **Метод:** GET
- **URL:** /api.php?id=1
- **Ответ:**
  
```json
  {
  "id": 1,
  "login": "username",
  "email": "user@example.com"}
```
![111](https://github.com/user-attachments/assets/c3095600-0b1b-4fcc-bd28-c3b78dc92d82)

### 3. Обновление информации пользователя
- **Метод:** PATCH
- **URL:** /api.php?id=1
- **Тело запроса:**
```json
{
  "email": "new_email@example.com"}
```

**Ответ:**

 ```json
{"message": "User updated successfully"}
   ```
![111](https://github.com/user-attachments/assets/b4ae8ddf-8ed7-40b1-92ef-c8f588d168c9)


 ### 4. Удаление пользователя
- **Метод:** DELETE
- **URL:** /api.php?id=1
- **Ответ:**
```json
{"message": "User deleted successfully"}
```
![111](https://github.com/user-attachments/assets/3375c27c-a3b4-4b2b-8e33-7df19f183d96)

### 5. Авторизация пользователя
- **Метод:** POST
- **URL:** /api.php?action=login
- **Тело запроса:**
```json
{
  "login": "username",
  "password": "password123"
}
```
- **Ответ:**
```json
  {"message": "Authorization successful", "user_id": 1}
```
![111](https://github.com/user-attachments/assets/592ea4fe-4008-4afa-98d9-ace72805047d)

