# API Documentation

## Overview

Aplikasi Koperasi Syariah menyediakan RESTful API yang lengkap untuk integrasi dengan sistem eksternal dan pengembangan aplikasi mobile.

## Access Points

### **Production API Documentation**
🔗 **Live API Docs**: [https://your-domain.com/docs](https://your-domain.com/docs)

### **Development API Documentation**
🔗 **Local API Docs**: [http://localhost:8003/docs](http://localhost:8003/docs)

### **Postman Collection**
📥 **Download**: [storage/app/scribe/collection.json](../storage/app/scribe/collection.json)

### **OpenAPI Specification**
📄 **Specification**: [storage/app/scribe/openapi.yaml](../storage/app/scribe/openapi.yaml)

---

## Authentication

### Bearer Token Authentication

API menggunakan Laravel Sanctum untuk authentication. Setiap request ke endpoint yang dilindungi harus menyertakan Bearer token.

```http
Authorization: Bearer {your_token_here}
```

### Getting Token

1. **Login via API**:
```http
POST /api/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "your_password"
}
```

**Response**:
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "user@example.com"
    },
    "token": "1|abc123def456..."
  }
}
```

### Token Usage

```http
GET /api/user
Authorization: Bearer 1|abc123def456...
```

---

## Base URL

### Environment URLs

| Environment | Base URL |
|-------------|----------|
| Production | `https://your-domain.com/api` |
| Staging | `https://staging.koperasi.com/api` |
| Development | `http://localhost:8003/api` |

---

## Response Format

### Success Response

```json
{
  "success": true,
  "data": {
    // Response data here
  },
  "message": "Operation successful"
}
```

### Error Response

```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "The given data was invalid.",
    "details": {
      "email": ["The email field is required."]
    }
  }
}
```

### HTTP Status Codes

| Status | Meaning |
|--------|---------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Internal Server Error |

---

## Rate Limiting

API memiliki rate limiting untuk mencegah abuse:

- **Standard users**: 100 requests per hour
- **Premium users**: 500 requests per hour
- **Admin users**: 1000 requests per hour

**Headers**:
```http
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 95
X-RateLimit-Reset: 1640995200
```

---

## Core Endpoints

### Authentication

#### Login
```http
POST /api/login
```

#### Register
```http
POST /api/register
```

#### Logout
```http
POST /api/logout
```

#### Refresh Token
```http
POST /api/token/refresh
```

#### Get User Profile
```http
GET /api/user
```

### Members (Anggota)

#### Get All Members
```http
GET /api/anggota
```

#### Get Member Detail
```http
GET /api/anggota/{id}
```

#### Create Member
```http
POST /api/anggota
```

#### Update Member
```http
PUT /api/anggota/{id}
```

#### Delete Member
```http
DELETE /api/anggota/{id}
```

### Savings (Simpanan)

#### Get Savings Types
```http
GET /api/jenis-simpanan
```

#### Get Member Savings
```http
GET /api/anggota/{id}/simpanan
```

#### Create Saving Transaction
```http
POST /api/transaksi/simpanan
```

#### Get Transaction History
```http
GET /api/anggota/{id}/simpanan/history
```

### Financing (Pembiayaan)

#### Get Financing Types
```http
GET /api/jenis-pembiayaan
```

#### Get Member Financing
```http
GET /api/anggota/{id}/pembiayaan
```

#### Submit Financing Application
```http
POST /api/pembiayaan/ajukan
```

#### Get Financing Detail
```http
GET /api/pembiayaan/{id}
```

#### Get Installment Schedule
```http
GET /api/pembiayaan/{id}/angsuran
```

#### Pay Installment
```http
POST /api/pembiayaan/{id}/bayar
```

### Reports

#### Get Savings Summary
```http
GET /api/reports/simpanan?period=daily&date=2024-12-11
```

#### Get Financing Portfolio
```http
GET /api/reports/pembiayaan?period=monthly&year=2024&month=12
```

#### Generate Financial Report
```http
GET /api/reports/financial?type=labarugi&period=monthly
```

---

## Data Types

### Date Format
Semua tanggal menggunakan format ISO 8601: `YYYY-MM-DD`

```json
{
  "tanggal_lahir": "1990-01-15",
  "tanggal_transaksi": "2024-12-11"
}
```

### DateTime Format
Datetime menggunakan format ISO 8601: `YYYY-MM-DDTHH:MM:SSZ`

```json
{
  "created_at": "2024-12-11T10:30:00+00:00",
  "updated_at": "2024-12-11T11:45:00+00:00"
}
```

### Currency Format
Semua nilai moneter dalam format desimal dengan 2 digit:

```json
{
  "jumlah": 1500000.00,
  "saldo": 12500000.50
}
```

---

## Pagination

Endpoint yang mengembalikan list data menggunakan pagination:

```json
{
  "success": true,
  "data": {
    "data": [
      // Array of items
    ],
    "current_page": 1,
    "from": 1,
    "last_page": 10,
    "per_page": 15,
    "to": 15,
    "total": 150
  }
}
```

### Pagination Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| page | integer | 1 | Page number |
| per_page | integer | 15 | Items per page (max: 100) |

---

## Filtering & Searching

### General Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| search | string | Search in name, email, no_anggota |
| sort | string | Sort field (name, created_at, etc) |
| order | string | Sort direction (asc, desc) |
| status | string | Filter by status |
| date_from | date | Start date filter |
| date_to | date | End date filter |

### Example

```http
GET /api/anggota?search=John&sort=nama_lengkap&order=asc&status=aktif&per_page=25
```

---

## Error Handling

### Common Error Codes

| Code | Description |
|------|-------------|
| VALIDATION_ERROR | Input validation failed |
| AUTHENTICATION_FAILED | Invalid credentials |
| AUTHORIZATION_FAILED | Insufficient permissions |
| NOT_FOUND | Resource not found |
| DUPLICATE_ENTRY | Data already exists |
| INSUFFICIENT_BALANCE | Insufficient funds |
| BUSINESS_RULE_VIOLATION | Business rule violation |

### Error Response Format

```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "The given data was invalid.",
    "details": {
      "email": ["The email field is required."],
      "no_hp": ["The no hp must be at least 10 characters."]
    }
  }
}
```

---

## SDKs & Libraries

### Official SDKs

#### JavaScript/Node.js
```bash
npm install koperasi-syariah-js
```

```javascript
import KoperasiAPI from 'koperasi-syariah-js';

const client = new KoperasiAPI({
  baseURL: 'https://your-domain.com/api',
  token: 'your_api_token'
});

// Get all members
const members = await client.anggota.list();
```

#### PHP
```bash
composer require koperasi-syariah/php
```

```php
use KoperasiSyariah\Client;

$client = new Client([
    'base_uri' => 'https://your-domain.com/api',
    'token' => 'your_api_token'
]);

// Get all members
$members = $client->anggota()->list();
```

---

## Webhooks

### Supported Events

API dapat mengirim webhook events untuk notify sistem eksternal:

#### Member Events
- `member.created` - New member registered
- `member.updated` - Member data updated
- `member.status_changed` - Member status changed

#### Transaction Events
- `transaction.created` - New transaction created
- `transaction.updated` - Transaction updated

#### Financing Events
- `financing.applied` - New financing application
- `financing.approved` - Financing approved
- `financing.disbursed` - Financing disbursed
- `financing.paid` - Installment paid

### Webhook Configuration

Configure webhook URLs di admin panel:

1. Menu **Settings** → **Webhooks**
2. Add new webhook URL
3. Select events to subscribe
4. System akan mengirim POST request ke URL tersebut

### Webhook Payload

```json
{
  "event": "member.created",
  "data": {
    "id": 123,
    "nama_lengkap": "John Doe",
    "email": "john@example.com",
    "no_anggota": "KOP2024001"
  },
  "timestamp": "2024-12-11T10:30:00+00:00"
}
```

---

## Testing

### Postman Collection
Download pre-configured Postman collection:
- Link: `storage/app/scribe/collection.json`
- Includes all endpoints with examples
- Pre-configured environment variables

### Sandbox Environment
Untuk testing gunakan sandbox environment:
- URL: `https://sandbox.koperasi.com/api`
- Test data tidak akan mempengaruhi production
- Reset harian otomatis

---

## Best Practices

### Performance
- Gunakan pagination untuk large datasets
- Implement caching untuk static data
- Gunakan fields parameter untuk select specific columns
- Compress responses dengan gzip

### Security
- Selalu gunakan HTTPS di production
- Validasi input di client side
- Implement retry logic untuk network errors
- Store tokens securely

### Error Handling
- Implement proper error handling
- Log API responses untuk debugging
- Use exponential backoff untuk retries
- Handle rate limits gracefully

### Caching
- Cache static data (jenis simpanan, jenis pembiayaan)
- Cache member data dengan TTL 5 menit
- Implement client-side caching
- Use conditional requests (ETag)

---

## Support

### Documentation Updates
- Documentation otomatis terupdate dengan code
- Changelog tersedia di [`../changelog/README.md`](../changelog/README.md)
- API breaking changes di-announce 30 hari sebelumnya

### Getting Help
- **API Documentation**: Visit `/docs` for interactive docs
- **Support Email**: api-support@koperasi.com
- **Status Page**: https://status.koperasi.com
- **GitHub Issues**: Report bugs di GitHub repository

### Community
- **Developer Forum**: https://dev.koperasi.com
- **Slack Channel**: #api-devs
- **Stack Overflow**: Tag dengan `koperasi-syariah-api`

---

## Version History

### v1.0.0 (2024-12-11)
- Initial API release
- 45+ endpoints
- Full CRUD operations
- Authentication & authorization
- Webhook support
- Comprehensive documentation

### Upcoming v1.1.0 (Q1 2025)
- GraphQL support
- Real-time events via WebSocket
- Advanced filtering capabilities
- Bulk operations
- API analytics dashboard

---

**Version**: 1.0.0
**Last Updated**: December 11, 2024
**API Version**: v1
**Base URL**: https://your-domain.com/api