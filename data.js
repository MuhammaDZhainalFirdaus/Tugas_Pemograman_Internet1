const initialProducts = [
    {
        id: 1,
        name: 'Syphon Coffee Maker Manual Brew Vacuum Pot',
        price: 511000,
        category: 'Manual Brew',
        stock: 15,
        image: 'https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=crop&w=400&q=80',
        description: 'Syphon Coffee Maker adalah alat seduh kopi manual yang menghasilkan kopi dengan cita rasa bersih dan jernih. Proses vakum uniknya mengekstrak minyak esensial kopi secara optimal.'
    },
    {
        id: 2,
        name: 'Electric Coffee Grinder - 650N',
        price: 1111000,
        category: 'Grinder',
        stock: 8,
        image: 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80',
        description: 'Grinder kopi elektrik dengan presisi tinggi untuk menghasilkan bubuk kopi yang konsisten, cocok untuk berbagai metode seduh.'
    },
    {
        id: 3,
        name: 'Coffee Server / Coffee Pot 01 Teko Server Kopi V60',
        price: 181000,
        category: 'Aksesoris',
        stock: 25,
        image: 'https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80',
        description: 'Teko server kaca tahan panas yang didesain khusus untuk metode pour-over V60, menjaga suhu kopi lebih lama.'
    },
    {
        id: 4,
        name: 'Vietnam Drip Alat Pembuat Kopi Vietnam',
        price: 181000,
        category: 'Manual Brew',
        stock: 30,
        image: 'https://images.unsplash.com/photo-1519864600265-abb23847ef2c?auto=format&fit=crop&w=400&q=80',
        description: 'Alat klasik untuk membuat kopi susu khas Vietnam dengan metode drip yang lambat dan menghasilkan rasa yang kuat.'
    }
];

const initialCategories = [
    { id: 1, name: 'Manual Brew' },
    { id: 2, name: 'Grinder' },
    { id: 3, name: 'Aksesoris' },
    { id: 4, name: 'Alat Seduh' },
    { id: 5, name: 'Filter' }
];

const initialOrders = [
    { id: 1, customerName: 'Andi', products: [{ name: 'Syphon Coffee Maker', quantity: 2 }], totalPrice: 1022000, status: 'Selesai', date: '2024-05-01' },
    { id: 2, customerName: 'Budi', products: [{ name: 'Electric Coffee Grinder', quantity: 1 }], totalPrice: 1111000, status: 'Proses', date: '2024-05-02' },
    { id: 3, customerName: 'Citra', products: [{ name: 'Vietnam Drip', quantity: 3 }], totalPrice: 543000, status: 'Batal', date: '2024-05-03' }
];

const initialCustomers = [
    { id: 1, name: 'Andi', email: 'andi@example.com', joinDate: '2024-04-15' },
    { id: 2, name: 'Budi', email: 'budi@example.com', joinDate: '2024-04-18' },
    { id: 3, name: 'Citra', email: 'citra@example.com', joinDate: '2024-04-21' },
    { id: 4, name: 'Penerima04', email: 'penerima04@example.com', joinDate: '2024-04-25' }
]; 