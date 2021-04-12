# EncurtadorDeUrl

An simple URLs shortener

## Usage

First of all, set your database parameters in "app/settings.php".

Then, just be sure you're the "public" directory an run in your terminal:
```
php -S localhost:8080
```

Now you're ready to use it!

## Requests

### Shorten your URL

```
Post
localhost:8080/
data: { "url" : "youtURL.com" }
```