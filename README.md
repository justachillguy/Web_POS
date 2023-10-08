# API DOCUMENTAION

## AUTHENTICATION

#### LOGIN (POST)

```http
  https://f.mmsdev.site/api/v1/login
```

| Arguments | Type   | Description                  |
| :-------- | :----- | :--------------------------- |
| email     | sting  | **Required** admin@gmail.com |
| password  | string | **Required** thepassword     |

##### Note : Tokens will be returned if login process is successful. But tokens will not be returned if a user is banned.

#### LOGOUT (POST)

```http
  https://f.mmsdev.site/api/v1/logout
```

#### LOGOUT ALL DEVICES (POST)

```http
  https://f.mmsdev.site/api/v1/logout-all
```

#### GET ALL DEVICES (GET)

```http
  https://f.mmsdev.site/api/v1/devices
```

---

## USER MANAGEMENT

###### Note : Only Admin can manage users. So basically, a normal staff cannot access these routes.

#### USERS LIST (GET)

```http
  https://f.mmsdev.site/api/v1/user
```

#### BANNED-USERS LIST (GET)

```http
  https://f.mmsdev.site/api/v1/user/banned-users
```

#### CREATE USER (REGISTER) (POST)

```http
  https://f.mmsdev.site/api/v1/user/register
```

| Arguments             | Type     | Description                        |
| :-------------------- | :------- | :--------------------------------- |
| name                  | string   | **Required** Joy                   |
| phone_number          | string   | **Requried** 092544411             |
| date_of_birth         | date     | **Required** 12.5.2000             |
| gender                | enum     | **Required** male                  |
| position              | enum     | **Required** admin                 |
| address               | longText | **Required** yangon                |
| email                 | string   | **Required** admin@gmail.com       |
| password              | string   | **Required** thepassword           |
| password_confirmation | string   | **Required** thepassword           |
| photo                 | string   | **Required** public/media/user.png |

###### Note : Only Admin can register and manage users.

#### USER PROFILE DETAILS (GET)

```http
  https://f.mmsdev.site/api/v1/user/details/{id}
```

| Arguments | Type    | Description    |
| :-------- | :------ | :------------- |
| id        | integer | **Required** 2 |

#### UPDATE USER'S POSITION (PUT)

```http
  https://f.mmsdev.site/api/v1/user/position-management/{id}
```

| Arguments | Type   | Description        |
| :-------- | :----- | :----------------- |
| position  | string | **Required** admin |

###### Note : Only Admin can change user's position.

#### BAN USER (PUT)

```http
  https://f.mmsdev.site/api/v1/user/ban/{id}
```

#### UNBAN USER (PUT)

```http
  https://f.mmsdev.site/api/v1/user/unban/{id}
```
---

## USER PROFILE

### CHANGE PASSWORD (POST)

```http
  https://f.mmsdev.site/api/v1/profile/change-password
```

| Arguments        | Type   | Description             |
| :--------------- | :----- | :---------------------- |
| current_password | string | **Required** asdffdsa   |
| new_password     | string | **Required** helloworld |
| confirm_password | string | **Required** helloworld |

#### USER INFO UPDATE (PUT)

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

## MEDIA

#### PHOTO LIST (GET)

```http
  https://f.mmsdev.site/api/v1/photo
```

#### STORE PHOTO (POST)

```http
  https://f.mmsdev.site/api/v1/photo
```

| Arguments | Type | Description            |
| :-------- | :--- | :--------------------- |
| photos[]  | file | **Required** apple.png |

###### Note : You need to put [] after photo parameter for uploading multiple files at once.

#### DELETE PHOTO (DELETE)

```http
  https://f.mmsdev.site/api/v1/photo/{id}
```

### MULTIPLE DELETE PHOTOS (POST)

```http
  https://f.mmsdev.site/api/v1/photo/multiple-delete
```

###### Note: Photo's ids have to be passed as an array

---

## Inventory

## BRAND

#### BRAND LIST (GET)

```http
  https://f.mmsdev.site/api/v1/brand
```
###### Note : You can search the record in the list by passing "keyword" parameter in route URL. Example below.

```http
  https://f.mmsdev.site/api/v1/brand&keyword=pepsi
```

###### Note : By default the list will be shown from the latest to earliest records. You can make it show from the earliest by passing just "id" parameter in route URL.

#### SHOW A PARTICULAR BRAND (GET)

```http
  https://f.mmsdev.site/api/v1/brand/{id}
```

#### STORE (or) CREATE A BRAND (POST)

```http
  https://f.mmsdev.site/api/v1/brand
```

| Arguments    | Type   | Description                  |
| :----------- | :----- | :--------------------------- |
| name         | string | **Required** Good Morning    |
| company      | string | **Required** Fresh Food      |
| agent        | string | **Required** Micheal Jordan  |
| phone_number | string | **Required** 0978787878      |
| information  | string | **Nullable** Founded in 1999 |
| photo        | file   | **Required** brand.png       |

#### UPDATE BRAND (PATCH)

```http
  https://f.mmsdev.site/api/v1/brand/{id}
```

| Arguments    | Type   | Description                  |
| :----------- | :----- | :--------------------------- |
| name         | string | **Nullable** Good Morning    |
| company      | string | **Nullable** Fresh Food      |
| agent        | string | **Nullable** Micheal Jordan  |
| phone_number | string | **Nullable** 0978787878      |
| information  | string | **Nullable** Founded in 1999 |
| photo        | file   | **Nullable** brand.png       |

#### DELETE BRAND (DELETE)

```http
  https://f.mmsdev.site/api/v1/brand/{id}
```

---

## PRODUCT

#### PRODUCT LIST (GET)

```http
  https://f.mmsdev.site/api/v1/product
```
###### Note : You can search the record in the list by passing "keyword" parameter in route URL. Example below.

```http
  https://f.mmsdev.site/api/v1/product&keyword=drink
```

###### Note : By default the list will be shown from the latest to earliest records. You can make it show from the earliest by passing just "id" parameter in route URL.

#### SHOW A PARTICULAR PRODUCT (GET)

```http
  https://f.mmsdev.site/api/v1/product/{id}
```

#### STORE PRODUCT (or) CREATE A NEW PRODUCT (Post)

```http
  https://f.mmsdev.site/api/v1/product
```

| Arguments        | Type    | Description                     |
| :--------------- | :------ | :------------------------------ |
| name             | string  | **Required** Juice              |
| brand_id         | integer | **Required** 1                  |
| actual_price     | integer | **Required** 3000               |
| sale_price       | integer | **Required** 3100               |
| unit             | string  | **Required** bottle             |
| photo            | string  | **Required** product.png        |

###### Note : As soon as a product is created and total stocks of it is defined, a new row in stocks table is added automatically as a stock record of that product.

#### UPDATE PRODUCT (PATCH)

```http
  https://f.mmsdev.site/api/v1/product/{id}
```

| Arguments        | Type    | Description                     |
| :--------------- | :------ | :------------------------------ |
| name             | string  | **Nullable** Juice              |
| brand_id         | integer | **Nullable** 1                  |
| actual_price     | integer | **Nullable** 3000               |
| sale_price       | integer | **Nullable** 3100               |
| unit             | string  | **Nullable** bottle             |
| more_information | string  | **Nullable** Do not press on it |
| photo            | string  | **Nullable** user.png           |

###### Note : After updating total stocks of a product, the increased stock amount of that product is stored as a new stock record of that product in stock tables.

#### DELETE PRODUCT (Delete)

```http
  https://f.mmsdev.site/api/v1/product/{id}
```

---

## STOCK

#### STOCK LIST (GET)

```http
  https://f.mmsdev.site/api/v1/stock
```
###### Note : You can search the record in the list by passing "keyword" parameter in route URL. Example below.

```http
  https://f.mmsdev.site/api/v1/stock&keyword=chocolate
```

###### Note : By default the list will be shown from the latest to earliest records. You can make it show from the earliest by passing just "id" parameter in route URL.

#### SHOW A PARTICULAR STOCK (GET)

```http
  https://f.mmsdev.site/api/v1/stock/{id}
```

#### STORE (OR) CREATE A NEW STOCK (POST)

```http
  https://f.mmsdev.site/api/v1/stock
```

| Arguments  | Type    | Description                 |
| :--------- | :------ | :-------------------------- |
| product_id | integer | **Required** 2              |
| quantity   | integer | **Required** 20             |

###### Note : After storing a stock, the amount of that stored quantity is added to the total stock of a respective product.

#### UPDATE STOCK (PATCH)

```http
  https://f.mmsdev.site/api/v1/stock/{id}
```

| Arguments  | Type    | Description                 |
| :--------- | :------ | :-------------------------- |
| product_id | integer | **Nullable** 2              |
| quantity   | integer | **Nullable** 20             |

---

## SALE

#### PRODUCTS LIST (GET)

```http
  https://f.mmsdev.site/api/v1/sale/products-list
```
#### CHECKOUT (POST)

```http
  https://f.mmsdev.site/api/v1/sale/checkout
```

###### NOTE: JSON of ids of products and quantity of them have to be passed from Frontend.

#### RECENT LIST (GET)

```http
  https://f.mmsdev.site/api/v1/sale/recent-list
```
###### NOTE: Today's recent sales list.

#### VOUCHER DETAILS (GET)

```http
  https://f.mmsdev.site/api/v1/voucher/{voucher_number}
```
  
#### CLOSE SALE & CREATE DAILY SALES (POST)

```http
  https://f.mmsdev.site/api/v1/sale/sale-close
```

#### CREATE MONTHLY SALES (POST)

```http
  https://f.mmsdev.site/api/v1/sale/sum-daily-sales
```
| Arguments  | Type     | Description                 |
| :--------- | :------  | :-------------------------- |
| date       | datetime | **Required** 2022-01        |

###### NOTE: Month and year are essential.

#### CREATE YEARLY SALES (POST)

```http
  https://f.mmsdev.site/api/v1/sale/sum-monthly-sales
```
| Arguments  | Type     | Description                 |
| :--------- | :------  | :-------------------------- |
| year       | datetime | **Required** 2022           |

---

## FINANCE

#### DAILY SALES LIST (GET)

```http
  https://f.mmsdev.site/api/v1/finance/daily-sales
```

#### MONTHLY SALES LIST (GET)

```http
  https://f.mmsdev.site/api/v1/finance/monthly-sales
```

#### YEARLY SALES LIST (GET)

```http
  https://f.mmsdev.site/api/v1/finance/yearly-sales
```

#### CUSTOM SALES LIST (GET)

```http
  https://f.mmsdev.site/api/v1/finance/custom-sales-list
```
###### NOTE: Start date and end date have to be selected and passed from frontend. So it wii be passed through param and sales list will be returned.

---
## REPORT
## SALE-REPORT

#### PRODUCT REPORT (GET)

```http
  https://f.mmsdev.site/api/v1/report/product-report
```

#### BRAND SALE REPORT (GET)

```http
  https://f.mmsdev.site/api/v1/report/brand-report
```

#### TODAY SALE REPORT (GET)

```http
  https://f.mmsdev.site/api/v1/report/today-report
```

#### WEEKLY SALE REPORT (GET)

```http
  https://f.mmsdev.site/api/v1/report/weekly-report
```

#### MONTHLY SALE REPORT (GET)

```http
  https://f.mmsdev.site/api/v1/report/monthly-report
```

#### YEARLY SALE REPORT (GET)

```http
  https://f.mmsdev.site/api/v1/report/yearly-report
```
---

## STOCK-REPORT

#### PRODUCT'S STOCK LEVEL TABLE (GET)

```http
  https://f.mmsdev.site/api/v1/report/stock-level-table
```
###### You can filter the list by stock level. All you have to do is passing one of these parameters ( in_stock, low_stock, out_of_stock) in route URL. Example below.

```http
  https://f.mmsdev.site/api/v1/report/stock-level-table?in_stock
```

###### Note : By default the list will be shown from the latest to earliest records. You can make it show from the earliest by passing just "id" parameter in route URL.

#### PRODUCT'S STOCK LEVEL BAR (GET)

```http
  https://f.mmsdev.site/api/v1/report/stock-level-bar
```

#### BEST SELLER BRANDS (GET)

```http
  https://f.mmsdev.site/api/v1/report/best-seller-brands
```
---

## For Overview Page

#### Total Stock, Total Staff and Today's Sales (GET)

```http
  https://f.mmsdev.site/api/v1/overview-page
```

#### Weekly Sales Overview (GET)

```http
  https://f.mmsdev.site/api/v1/weekly-overview
```

#### Weekly Sales Overview (GET)

```http
  https://f.mmsdev.site/api/v1/weekly-overview
```

#### Monthly Sales Overview (GET)

```http
  https://f.mmsdev.site/api/v1/monthly-overview
```

#### Yearly Sales Overview (GET)

```http
  https://f.mmsdev.site/api/v1/yearly-overview
```

---
