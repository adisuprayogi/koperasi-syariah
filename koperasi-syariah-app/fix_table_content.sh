#!/bin/bash

echo "Optimizing table content layout to prevent text overlapping..."

# List of table files to optimize
TABLE_FILES=(
    "resources/views/pengurus/pengajuan/index.blade.php"
    "resources/views/pengurus/pembiayaan/index.blade.php"
    "resources/views/anggota/pengajuan/index.blade.php"
    "resources/views/admin/jenis-simpanan/index.blade.php"
    "resources/views/admin/jenis-pembiayaan/index.blade.php"
    "resources/views/admin/pengurus/index.blade.php"
)

# Function to fix pengajuan table columns
fix_pengajuan_columns() {
    local file="$1"
    echo "Fixing pengajuan table columns in $file..."

    # Update header with specific widths
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 10%;">Kode<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anggota<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 20%;">Anggota<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Pembiayaan<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 18%;">Jenis Pembiayaan<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 12%;">Jumlah<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenor<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 8%;">Tenor<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 12%;">Tanggal<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 12%;">Status<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi<\/th>/<th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 8%;">Aksi<\/th>/' "$file"

    # Fix table data cells - remove whitespace-nowrap and add truncate
    sed -i '' 's/class="px-3 py-3 whitespace-nowrap"/class="px-3 py-3 whitespace-nowrap truncate"/g' "$file"

    echo "Fixed $file"
}

# Function to fix jenis-simpanan table columns
fix_jenis_simpanan_columns() {
    local file="$1"
    echo "Fixing jenis simpanan table columns in $file..."

    # Update headers with proper widths
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 8%;">Kode<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Simpanan<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 20%;">Nama Simpanan<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 10%;">Tipe<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nisbah<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 8%;">Nisbah<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batas Setoran<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 15%;">Batas Setoran<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarik<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 8%;">Tarik<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 8%;">Status<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi<\/th>/<th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 15%;">Aksi<\/th>/' "$file"

    # Fix table data cells padding
    sed -i '' 's/px-6 py-4/px-3 py-3/g' "$file"

    # Add truncate to cells
    sed -i '' 's/whitespace-nowrap>/whitespace-nowrap truncate>/g' "$file"

    # Fix double truncate if exists
    sed -i '' 's/whitespace-nowrap truncate truncate/whitespace-nowrap truncate/g' "$file"

    # Change text-right to text-center for action column
    sed -i '' 's/text-right text-sm font-medium/text-center text-sm font-medium/g' "$file"

    echo "Fixed $file"
}

# Function to fix pembiayaan table columns
fix_pembiayaan_columns() {
    local file="$1"
    echo "Fixing pembiayaan table columns in $file..."

    # Update headers with proper widths
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 10%;">Kode<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anggota<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 18%;">Anggota<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 15%;">Jenis<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plafond<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 12%;">Plafond<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenor<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 8%;">Tenor<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 10%;">Tanggal<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Angsuran<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 10%;">Angsuran<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 10%;">Status<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi<\/th>/<th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 7%;">Aksi<\/th>/' "$file"

    # Fix table data cells
    sed -i '' 's/px-6 py-4/px-3 py-3/g' "$file"
    sed -i '' 's/whitespace-nowrap>/whitespace-nowrap truncate>/g' "$file"
    sed -i '' 's/whitespace-nowrap truncate truncate/whitespace-nowrap truncate/g' "$file"

    echo "Fixed $file"
}

# Function to fix admin pengurus table
fix_admin_pengurus_columns() {
    local file="$1"
    echo "Fixing admin pengurus table columns in $file..."

    # Update headers with proper widths
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lengkap<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 20%;">Nama Lengkap<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 20%;">Email<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. HP<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 15%;">No. HP<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posisi<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 15%;">Posisi<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status<\/th>/<th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 10%;">Status<\/th>/' "$file"
    sed -i '' 's/<th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi<\/th>/<th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 20%;">Aksi<\/th>/' "$file"

    # Fix table data cells
    sed -i '' 's/px-6 py-4/px-3 py-3/g' "$file"
    sed -i '' 's/whitespace-nowrap>/whitespace-nowrap truncate>/g' "$file"
    sed -i '' 's/whitespace-nowrap truncate truncate/whitespace-nowrap truncate/g' "$file"
    sed -i '' 's/text-right text-sm font-medium/text-center text-sm font-medium/g' "$file"

    echo "Fixed $file"
}

# Process each file with the appropriate fix
for file in "${TABLE_FILES[@]}"; do
    if [[ -f "$file" ]]; then
        # Apply different fixes based on file type
        if [[ "$file" == *"pengajuan"* ]]; then
            fix_pengajuan_columns "$file"
        elif [[ "$file" == *"jenis-simpanan"* ]]; then
            fix_jenis_simpanan_columns "$file"
        elif [[ "$file" == *"pembiayaan"* ]]; then
            fix_pembiayaan_columns "$file"
        elif [[ "$file" == *"admin/pengurus"* ]]; then
            fix_admin_pengurus_columns "$file"
        else
            # Generic fix for other tables
            echo "Applying generic fixes to $file..."
            sed -i '' 's/px-6 py-4/px-3 py-3/g' "$file"
            sed -i '' 's/whitespace-nowrap>/whitespace-nowrap truncate>/g' "$file"
            sed -i '' 's/whitespace-nowrap truncate truncate/whitespace-nowrap truncate/g' "$file"
            sed -i '' 's/text-right text-/text-center text-/g' "$file"
        fi
    else
        echo "Warning: File $file not found, skipping..."
    fi
done

echo "Table content optimization completed!"