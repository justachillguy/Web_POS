# API Documentation

## Authentication

#### Login (POST)

```http
  https://f.mmsdev.site/api/v1/login
```

| Arguments | Type   | Description                  |
| :-------- | :----- | :--------------------------- |
| email     | sting  | **Required** admin@gmail.com |
| password  | string | **Required** thepassword     |

#### Logout (POST)

```http
  https://f.mmsdev.site/api/v1/logout
```

#### Logout from all devices(POST)

```http
  https://f.mmsdev.site/api/v1/logout-all
```

#### Get All Devices (GET)

```http
  https://f.mmsdev.site/api/v1/devices
```

---

## User Management

#### Get All Users (GET)

```http
  https://f.mmsdev.site/api/v1/users
```

#### Create Users (Register) (POST)

```http
  https://f.mmsdev.site/api/v1/register
```

| Arguments             | Type     | Description                  |
| :-------------------- | :------- | :--------------------------- |
| name                  | string   | **Required** Joy             |
| phone_number          | string   | **Requried** 092544411       |
| date_of_birth         | date     | **Required** 12.5.2005       |
| gender                | enum     | **Required** male            |
| position              | enum     | **Required** admin           |
| address               | longText | **Required** yangon          |
| email                 | string   | **Required** admin@gmail.com |
| password              | string   | **Required** adfdafda        |
| password_confirmation | string   | **Required** adfdafda        |

##### Note : Only Admin can register and manage users.

#### Update User's Role (PUT)

```http
  https://f.mmsdev.site/api/v1/users/{id}
```

| Arguments | Type | Description        |
| :-------- | :--- | :----------------- |
| position  | enum | **Required** admin |

###### Note : Only Admin can change user's role.

---

## Products

#### Get All Products (GET)

```http
  https://f.mmsdev.site/api/v1/product
```

#### Show A Particular Product (GET)

```http
  https://f.mmsdev.site/api/v1/product/{id}
```

#### Store Product (Post)

```http
  https://f.mmsdev.site/api/v1/product
```

| Arguments        | Type    | Description                     |
| :--------------- | :------ | :------------------------------ |
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
  https://f.mmsdev.site/api/v1/product/{id}
```

| Arguments        | Type    | Description                     |
| :--------------- | :------ | :------------------------------ |
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
  https://f.mmsdev.site/api/v1/product/{id}
```

---

## Brands

#### Get All Brands (GET)

```http
  https://f.mmsdev.site/api/v1/brand
```

#### Show A Particular Brand (GET)

```http
  https://f.mmsdev.site/api/v1/brand/{id}
```

#### Store Brand (Post)

```http
  https://f.mmsdev.site/api/v1/brand
```

| Arguments   | Type   | Description                  |
| :---------- | :----- | :--------------------------- |
| name        | string | **Required** Good Morning    |
| company     | string | **Required** Fresh Food      |
| information | string | **Required** Founded in 1999 |
| photo       | string | **Nullable** user.png        |

#### Update Brand (Patch)

```http
  https://f.mmsdev.site/api/v1/brand/{id}
```

| Arguments   | Type   | Description                  |
| :---------- | :----- | :--------------------------- |
| name        | string | **Required** Good Morning    |
| company     | string | **Required** Fresh Food      |
| information | string | **Required** Founded in 1999 |
| photo       | string | **Nullable** user.png        |

#### Delete Brand (Delete)

```http
  https://f.mmsdev.site/api/v1/brand/{id}
```

---

## Stocks

#### Get All Stocks (GET)

```http
  https://f.mmsdev.site/api/v1/stock
```

#### Show A Particular Stock (GET)

```http
  https://f.mmsdev.site/api/v1/stock/{id}
```

#### Store Stock (POST)

```http
  https://f.mmsdev.site/api/v1/stock
```

| Arguments  | Type    | Description                 |
| :--------- | :------ | :-------------------------- |
| product_id | integer | **Required** 2              |
| quantity   | integer | **Required** 20             |
| more       | string  | **Required** blah blah blah |

##### Note : After storing a stock, the amount of that stored quantity is added to the total stock of a respective product.

#### Update Stock (Patch)

```http
  https://f.mmsdev.site/api/v1/stock/{id}
```

| Arguments  | Type    | Description                 |
| :--------- | :------ | :-------------------------- |
| product_id | integer | **Required** 2              |
| quantity   | integer | **Required** 20             |
| more       | string  | **Required** blah blah blah |

#### Delete Stock (Delete)

```http
  https://f.mmsdev.site/api/v1/stock/{id}
```

## Media

#### Get All Media (GET)

```http
  https://f.mmsdev.site/api/v1/photos
```

#### Store Photo (POST)

```http
  https://f.mmsdev.site/api/v1/photos
```

| Arguments | Type   | Description                           |
| :-------- | :----- | :------------------------------------ |
| url       | string | **Required** public/media/example.png |
| name      | string | **Required** apple                    |
| extension | string | **Required** png                      |

#### Delete Photo (Delete)

```http
  https://f.mmsdev.site/api/v1/photos/{id}
```

### Multiple Delete Photos (Multi Delete Photos)

```http
  https://f.mmsdev.site/api/v1/multiple-delete-photos
```

###### Note: Photo's ids have to be passed as an array

## User's Profile

### Change Password (POST)

```http
  https://f.mmsdev.site/api/v1/change-password
```

| Arguments        | Type   | Description             |
| :--------------- | :----- | :---------------------- |
| current_password | string | **Required** asdffdsa   |
| new_password     | string | **Required** helloworld |
| confirm_password | string | **Required** helloworld |

#### User's info Update (PUT)

```http
  https://f.mmsdev.site/api/v1/profile/{id}
```

| Arguments     | Type     | Description                          |
| :------------ | :------- | :----------------------------------- |
| name          | string   | **Nullable** Joy                     |
| phone_number  | string   | **Nullable** 092544411               |
| date_of_birth | date     | **Nullable** 12.5.2005               |
| gender        | enum     | **Nullable** male                    |
| address       | longText | **Nullable** yangon                  |
| photo         | string   | **Nullable** public/media/flower.png |

---
