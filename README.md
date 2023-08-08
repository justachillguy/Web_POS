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

---
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
---
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

##### Note : As soon as a product is created and total stocks of it is defined, a new row in stocks table is added automatically as a stock record of that product.

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

##### Note : After updating total stocks of a product, the increased stock amount of that product is stored as a new stock record of that product in stock tables.

#### Delete product (Delete)

```http
  http://127.0.0.1:8000/api/v1/product/{id}
```

---

## Brands

#### Get All Brands (GET)

```http
  http://127.0.0.1:8000/api/v1/brand
```


#### Show A Particular Brand (GET)

```http
  http://127.0.0.1:8000/api/v1/brand/{id}
```

#### Store Brand (Post)

```http
  http://127.0.0.1:8000/api/v1/brand
```

| Arguments        | Type    | Description                     |
| :-----------     | :------ | :-----------------------------  |
| name             | string  | **Required** Good Morning       |
| company          | string  | **Required** Fresh Food         |
| information      | string  | **Required** Founded in 1999    |
| photo            | string  | **Nullable** user.png           |

#### Update Brand (Patch)

```http
  http://127.0.0.1:8000/api/v1/brand/{id}
```

| Arguments        | Type    | Description                     |
| :-----------     | :------ | :-----------------------------  |
| name             | string  | **Required** Good Morning       |
| company          | string  | **Required** Fresh Food         |
| information      | string  | **Required** Founded in 1999    |
| photo            | string  | **Nullable** user.png           |


#### Delete Brand (Delete)

```http
  http://127.0.0.1:8000/api/v1/brand/{id}
```

---

## Stocks

#### Get All Stocks (GET)

```http
  http://127.0.0.1:8000/api/v1/stock
```


#### Show A Particular Stock (GET)

```http
  http://127.0.0.1:8000/api/v1/stock/{id}
```

#### Delete Stock (Delete)

```http
  http://127.0.0.1:8000/api/v1/stock/{id}
```

---


