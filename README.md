I decided not to use `api-platform` because in this case the task could be done with a very small amount of code.

And I do not use `fos-rest-bundle` with the same reason

I could use `nelmio/api-doc-bundle` for documentation generation but I think readme file is enough

PHPUnit tests is written only for `ArticleService` as an example

`XML` format is also supported. Just add the `Content-Type: application/xml` header

### install
- configure apache/nginx/whatever to point to `./public/index.php`
- change db url in `.env` file
- run `make install`
- do requests!

### tests
- `make phpunit`
---
### documentation

**getArticles**

path: `/articles`

method: `GET`

query parameters: 
- `limit` (20)
- `page` (1)
- `order` (ASC)
- `order_by` (id)

response:
````
{
	"page": 1,
	"limit": 20,
	"order": "asc",
	"order_by": "id",
	"total": 34,
	"collection": []
}
````

---

**getArticle**

path: `/article/{id}`

method: `GET`

response:
````
{
	"id": 1,
	"title": "Lorem ipsum dolor sit amet",
	"body": "Lorem ipsum dolor sit amet",
	"created_at": "2020 - 03 - 30 T04: 25: 58 - 07: 00 ",
	"updated_at": "2020 - 03 - 30 T04: 25: 58 - 07: 00"
}
````

---

**createArticle**

_`token` parameter should be provided with value `secret`_

path: `/article`

method: `POST`

query parameters:
- `token`

request:
````
{
	"title": "Lorem ipsum dolor sit amet",
	"body": "Lorem ipsum dolor sit amet"
}
````
response:
````
{
	"id": 1,
	"title": "Lorem ipsum dolor sit amet",
	"body": "Lorem ipsum dolor sit amet",
	"created_at": "2020 - 03 - 30 T04: 25: 58 - 07: 00 ",
	"updated_at": "2020 - 03 - 30 T04: 25: 58 - 07: 00"
}
````

---

**updateArticle**

_`token` parameter should be provided with value `secret`_

path: `/article/{id}`

method: `PUT`

query parameters:
- `token`

request:
````
{
	"title": "Lorem ipsum dolor sit amet",
	"body": "Lorem ipsum dolor sit amet"
}
````
response:
````
{
	"id": 1,
	"title": "Lorem ipsum dolor sit amet",
	"body": "Lorem ipsum dolor sit amet",
	"created_at": "2020 - 03 - 30 T04: 25: 58 - 07: 00 ",
	"updated_at": "2020 - 03 - 30 T04: 25: 58 - 07: 00"
}
````

---

**deleteArticle**

_`token` parameter should be provided with value `secret`_

path: `/article/{id}`

method: `DELETE`

query parameters:
- `token`

response: `empty`