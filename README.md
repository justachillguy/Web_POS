# API Documentation
## Authentication
#### Login (POST)

```http
  http://127.0.0.1:8000/api/v1/login
```

| Arguments | Type   | Description                  |
| :-------- | :----- | :--------------------------- |
| email     | sting  | **Required** admin@gmail.com |
| password  | string | **Required** adfdafda        |

#### Register (POST)

```http
  http://127.0.0.1:8000/api/v1/register
```

| Arguments             | Type   | Description                  |
| :-------------------- | :----- | :--------------------------- |
| name                  | string | **Required** Joy             |
| email                 | sting  | **Required** admin@gmail.com |
| password              | string | **Required** adfdafda        |
| password_confirmation | string | **Required** adfdafda        |

## User Profile

#### Logout (POST)

```http
  http://127.0.0.1:8000/api/v1/logout
```

#### Logout from all devices(POST)

```http
  http://127.0.0.1:8000/api/v1/logout-all
```

#### Get Devices (GET)

```http
  http://127.0.0.1:8000/api/v1/devices
```

## Products

#### Get All Products (GET)

```http
  http://127.0.0.1:8000/api/v1/product
```


#### Show A Particular Product (GET)

```http
  http://127.0.0.1:8000/api/v1/product/{id}
```

#### Store Product (Post)

```http
  http://127.0.0.1:8000/api/v1/product
```

| Arguments        | Type    | Description                     |
| :-----------     | :------ | :-----------------------------  |
| name             | string  | **Required** Juice              |
| brand_id         | integer | **Required** 1                  |
| actual_price     | integer | **Required** 3000               |
| sale_price       | integer | **Required** 3100               |
| total_stock      | integer | **Required** 20                 |
| unit             | string  | **Required** bottle             |
| more_information | string  | **Required** Do not press on it |
| photo            | string  | **Nullable** user.png           |

#### Update product (Patch)

```http
  http://127.0.0.1:8000/api/v1/product/{id}
```

| Arguments        | Type    | Description                     |
| :-----------     | :------ | :-----------------------------  |
| name             | string  | **Required** Juice              |
| brand_id         | integer | **Required** 1                  |
| actual_price     | integer | **Required** 3000               |
| sale_price       | integer | **Required** 3100               |
| total_stock      | integer | **Required** 20                 |
| unit             | string  | **Required** bottle             |
| more_information | string  | **Required** Do not press on it |
| photo            | string  | **Nullable** user.png           |


#### Delete product (Delete)

```http
  http://127.0.0.1:8000/api/v1/product/{id}
```

