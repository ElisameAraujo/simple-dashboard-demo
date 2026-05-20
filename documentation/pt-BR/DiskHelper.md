# 🖴 DiskHelper

A classe **DiskHelper** fornece funções utilitárias para manipular arquivos nos discos configurados no Laravel (`config/filesystems.php`).  
Ela simplifica operações comuns como salvar, atualizar, remover, obter tamanho, baixar e gerar URL pública de arquivos.

---

## 📂 Funções disponíveis

### `saveFile($file, ?string $disk = 'public', array|string|null $subfolders = null): string`

Salva um arquivo dentro da estrutura de discos do Laravel.

-   `$file`: Arquivo que será salvo (instância de `UploadedFile`).
-   `$disk`: Disco em que o arquivo será salvo. Opcional, padrão `public`.
-   `$subfolders`: Subpasta ou array de subpastas dentro do disco. Opcional.

O nome do arquivo é gerado automaticamente com um identificador único.  
Retorna o **caminho relativo** do arquivo dentro do disco.

```php
DiskHelper::saveFile($file, 'meu-disco');
// "meu-disco/meu-arquivo-salvo-20251218170832.jpg"

DiskHelper::saveFile($file, 'meu-disco', 'uploads');
// "meu-disco/uploads/meu-arquivo-salvo-20251218170832.jpg"

DiskHelper::saveFile($file, 'meu-disco', ['uploads','reports','2015','dec','21']);
// "meu-disco/uploads/reports/2015/dec/21/meu-arquivo-salvo-20251218170832.jpg"
```

---

### `updateFile($file, string $oldFile, ?string $disk = 'public', array|string|null $subfolders = null): string`

Substitui um arquivo existente por outro.

-   `$file`: Novo arquivo.
-   `$oldFile`: Caminho do arquivo antigo que será removido.
-   `$disk`: Disco onde o arquivo está armazenado. Opcional, padrão `public`.
-   `$subfolders`: Subpasta(s) onde o arquivo está armazenado. Opcional.

Retorna o caminho do novo arquivo.

```php
DiskHelper::updateFile($file, 'old.jpg');
// "new-file-20251218173922.jpg"

DiskHelper::updateFile($file, 'uploads/old.jpg', 'meu-disco');
// "uploads/new-file-20251218173922.jpg"
```

---

### `removeFile(string $file, ?string $disk = 'public', array|string|null $subfolders = null): bool`

Remove um arquivo salvo no disco.

-   `$file`: Caminho do arquivo.
-   `$disk`: Disco onde o arquivo está armazenado. Opcional, padrão `public`.
-   `$subfolders`: Subpasta(s) onde o arquivo está armazenado. Opcional.

Retorna `true` se o arquivo foi removido, `false` caso contrário.

```php
DiskHelper::removeFile('file.jpg');
// true

DiskHelper::removeFile('uploads/file.jpg', 'meu-disco');
// true
```

---

### `fileSize(string $file, ?string $disk = 'public', array|string|null $subfolders = null): ?string`

Retorna o tamanho formatado de um arquivo salvo no disco.

-   `$file`: Caminho do arquivo.
-   `$disk`: Disco onde o arquivo está armazenado. Opcional, padrão `public`.
-   `$subfolders`: Subpasta(s) onde o arquivo está armazenado. Opcional.

Retorna uma string formatada (ex: `"256 KB"`) ou `null` se o arquivo não existir.

```php
DiskHelper::fileSize('image.jpg');
// "256 KB"

DiskHelper::fileSize('image.jpg', 'meu-disco', ['archives','2025','dec','21']);
// "256 KB"
```

---

### `fileUrl(string $file, ?string $disk = 'public', array|string|null $subfolders = null): ?string`

Retorna a URL pública de um arquivo armazenado em um disco.

-   `$file`: Caminho do arquivo.
-   `$disk`: Disco onde o arquivo está armazenado. Opcional, padrão `public`.
-   `$subfolders`: Subpasta(s) onde o arquivo está armazenado. Opcional.

Retorna a URL pública ou `null` se o arquivo não existir.

```php
DiskHelper::fileUrl('image.jpg');
// "https://meusite.com/storage/image.jpg"

DiskHelper::fileUrl('uploads/image.jpg', 'meu-disco');
// "https://meusite.com/storage/meu-disco/uploads/image.jpg"
```

---

## ✅ Observações importantes

-   O parâmetro `$disk` sempre se refere ao disco configurado em `config/filesystems.php`.
-   O parâmetro `$subfolders` pode ser string (`'uploads'`) ou array (`['uploads','2025','dec']`).
-   O retorno de `saveFile` é sempre o caminho relativo dentro do disco, sem prefixo `public/`.
-   Para exibir arquivos em views, use `fileUrl()`.
-   Para baixar arquivos, use `downloadFile()`.
