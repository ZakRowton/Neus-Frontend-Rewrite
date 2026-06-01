# NEUS Frontend Rewrite

A complete PHP/JavaScript/CSS rewrite of the NEUS Network frontend, migrating from Next.js to a PHP-based architecture with modern JavaScript SPA functionality.

## Overview

This project replicates all NEUS frontend functionality using:
- **PHP** - Server-side rendering, routing, API proxy
- **JavaScript** - Client-side interactivity, SPA navigation, wallet integration
- **Tailwind CSS** - Utility-first styling matching NEUS dark theme
- **Bootstrap 5** - Selective component usage
- **Apache mod_rewrite** - Clean URL routing

## Features

### Implemented
- ✅ Complete routing system (mirrors Next.js App Router)
- ✅ Authentication (email/password + wallet connect)
- ✅ Dashboard with stats and activity feed
- ✅ Proof system (create, view, verify, library)
- ✅ Agent system (create, list, detail, link)
- ✅ Zeus AI Chat interface
- ✅ Profile management
- ✅ Credits system
- ✅ Admin panel
- ✅ Genesis campaign page
- ✅ Multi-chain wallet support
- ✅ Cosmos DB integration layer
- ✅ SDK Bridge (PHP proxy to NEUS API)
- ✅ API Proxy for authenticated requests
- ✅ Dark luxury theme (exact NEUS styling)
- ✅ Responsive design
- ✅ Real-time UI updates
- ✅ Toast notifications
- ✅ Loading states
- ✅ Form validation
- ✅ CSRF protection

### Architecture

```
Neus-Frontend-Rewrite/
├── .htaccess              # Apache URL rewriting
├── index.php              # Main entry point / SPA router
├── config/
│   ├── config.php         # Central configuration
│   └── routes.php         # Route definitions
├── includes/
│   ├── functions.php      # Core utilities
│   ├── auth.php           # Authentication system
│   ├── database.php       # Cosmos DB + Local DB layer
│   └── sdk.php            # SDK Bridge
├── components/
│   ├── header.php         # Navigation header
│   ├── footer.php         # Footer
│   └── sidebar.php        # Dashboard sidebar
├── pages/
│   ├── landing.php        # Homepage
│   ├── genesis.php        # Genesis campaign
│   ├── dashboard.php      # User dashboard
│   ├── auth/
│   │   ├── login.php
│   │   ├── signup.php
│   │   ├── wallet-connect.php
│   │   └── logout.php
│   ├── proofs/
│   │   ├── index.php
│   │   ├── create.php
│   │   └── detail.php
│   ├── agents/
│   │   ├── index.php
│   │   ├── create.php
│   │   └── link.php
│   ├── agent/
│   │   └── detail.php
│   ├── chat/
│   │   ├── index.php
│   │   └── history.php
│   ├── profile/
│   │   ├── index.php
│   │   ├── edit.php
│   │   ├── security.php
│   │   └── linked-accounts.php
│   ├── credits/
│   │   ├── index.php
│   │   ├── buy.php
│   │   └── history.php
│   ├── admin/
│   │   ├── index.php
│   │   ├── users.php
│   │   ├── proofs.php
│   │   ├── agents.php
│   │   ├── settings.php
│   │   └── observability.php
│   ├── verify/
│   │   ├── index.php
│   │   └── detail.php
│   ├── identity/
│   │   └── index.php
│   ├── about.php
│   ├── contact.php
│   ├── docs/
│   │   └── index.php
│   └── 404.php
├── api/
│   ├── proxy.php          # API proxy to NEUS backend
│   └── endpoints.php      # Local AJAX endpoints
├── assets/
│   ├── css/
│   │   └── neus-theme.css   # Main stylesheet
│   └── js/
│       └── app.js           # Main JavaScript app
├── data/                  # Local file-based DB (fallback)
├── cache/                 # File-based cache
├── logs/                  # Activity logs
└── README.md
```

## Installation

### Requirements
- PHP 8.1+
- Apache with mod_rewrite
- Composer (optional)

### Setup
1. Clone the repository
2. Configure Apache to point to the project root
3. Ensure `.htaccess` is enabled
4. Set environment variables in `.env`:

```env
NEUS_API_BASE=https://api.neus.network
NEUS_API_VERSION=v1
COSMOS_ENDPOINT=https://your-cosmos-db.documents.azure.com:443/
COSMOS_KEY=your-cosmos-key
COSMOS_DATABASE=neus-local
```

5. Access the site via your configured domain

## Configuration

### Environment Variables
| Variable | Description | Default |
|----------|-------------|---------|
| `NEUS_API_BASE` | NEUS backend API URL | `https://api.neus.network` |
| `NEUS_API_VERSION` | API version | `v1` |
| `COSMOS_ENDPOINT` | Azure Cosmos DB endpoint | - |
| `COSMOS_KEY` | Cosmos DB key | - |
| `COSMOS_DATABASE` | Database name | `neus-local` |
| `NEUS_MCP_ENDPOINT` | MCP server URL | - |
| `NEUS_MCP_API_KEY` | MCP API key | - |

### Feature Flags
Features can be enabled/disabled in `config/config.php`:
- `FEATURE_GENESIS_CAMPAIGN`
- `FEATURE_PROOF_CREATION`
- `FEATURE_REAL_TIME_UPDATES`
- `FEATURE_MARKET_DATA`
- `FEATURE_ZEUS_AI`
- `FEATURE_AGENT_SYSTEM`
- `FEATURE_CREDITS_SYSTEM`

## API Integration

The frontend proxies all API requests through `/api/proxy.php` which:
1. Forwards authentication tokens
2. Handles CORS
3. Implements rate limiting
4. Returns JSON responses

### SDK Usage
```php
$sdk = neusSdk();

// Create a proof
$result = $sdk->createProof([
    'verifierId' => 'ownership-basic',
    'title' => 'My Proof',
    'walletAddress' => '0x...',
]);

// List agents
$agents = $sdk->listAgents();

// Chat with Zeus
$response = $sdk->chat('How do I create a proof?');
```

## Theme

The NEUS dark luxury theme features:
- **Colors**: Gold (#D4AF37), Cream (#F9F1D8), Dark (#0a0a0a)
- **Typography**: Inter (sans), JetBrains Mono (monospace)
- **Effects**: Glass panels, noise overlay, gold glow, scanner animations
- **Responsive**: Mobile-first with Tailwind breakpoints

## Supported Blockchains

- Ethereum (chainId: 1)
- Polygon (chainId: 137)
- Arbitrum (chainId: 42161)
- Base (chainId: 8453)
- Optimism (chainId: 10)
- BSC (chainId: 56)

## Security

- CSRF token validation on all forms
- Session-based authentication with secure cookies
- Rate limiting (100 requests/minute)
- Input sanitization and validation
- XSS protection via output escaping
- Secure session handling

## License

MIT License - NEUS Network

## Credits

Built by the NEUS Network team. Sovereign Identity Layer.
