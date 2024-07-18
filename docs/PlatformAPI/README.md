# Turso Platform API

The Turso API gives you everything needed to manage your organization and its members, groups, databases, and API tokens.

If you want to programatically create and manage databases, either for building a platform where you provide SQLite databases to your users or have a per-user SQLite database architecture, this is the API to do that.

## Quickstart

Make sure to install the Turso CLI if you haven’t already. And Signup or Login if you already have Turso account:

**Signup**
```
turso auth signup
```

**Login**
```
turso auth login
```

Now create a new API Token using the Turso CLI:
```
turso auth api-tokens mint quickstart
```

Copy and Paste in your `.env` file:
```env
API_PLATFORM_TOKEN=your_api_token
```
