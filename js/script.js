// Sample product data
const products = [
    {
        id: 1,
        name: 'iPhone 14 Pro Max',
        price: 29990000,
        oldPrice: 32990000,
        image: 'https://mobileworld.com.vn/uploads/news/09_2022/47640-98874-iPhone-14-Pro-Colors-xl.jpg',
        description: 'Màn hình Super Retina XDR 6.7 inch, chip A16 Bionic, camera chính 48MP'
    },
    {
        id: 2,
        name: 'Samsung Galaxy S23 Ultra',
        price: 26990000,
        oldPrice: 28990000,
        image: 'https://www.sammyfans.com/wp-content/uploads/2023/01/s23-series-leaked-promo-img.jpg',
        description: 'Màn hình Dynamic AMOLED 6.8 inch, chip Snapdragon 8 Gen 2, bút S Pen tích hợp'
    },
    {
        id: 3,
        name: 'Xiaomi 13 Pro',
        price: 22990000,
        oldPrice: 24990000,
        image: 'https://photo2.tinhte.vn/data/attachment-files/2022/12/6240762_Xiaomi_13_Pro.5.jpg',
        description: 'Màn hình AMOLED 6.73 inch, chip Snapdragon 8 Gen 2, camera Leica'
    },
    {
        id: 4,
        name: 'OPPO Find X5 Pro',
        price: 19990000,
        oldPrice: 22990000,
        image: 'https://vatvostudio.vn/wp-content/uploads/2022/02/OPPO-Find-X5-Pro-leak-1.jpg',
        description: 'Màn hình LTPO2.0 6.7 inch, chip Snapdragon 8 Gen 1, sạc nhanh 80W'
    },
    {
        id: 5,
        name: 'Vivo X90 Pro+',
        price: 28990000,
        oldPrice: 30990000,
        image: 'https://specphone.com/web/wp-content/uploads/2023/05/X90-Pro-Pro-Photography-in-Pocket-1.jpg',
        description: 'Màn hình AMOLED 6.78 inch, chip Dimensity 9200, camera chính 50MP'
    },
    {
        id: 6,
        name: 'Google Pixel 7 Pro',
        price: 23990000,
        oldPrice: 25990000,
        image: 'https://sohanews.sohacdn.com/thumb_w/1000/160588918557773824/2023/1/14/photo-2-1673657681689188016985.jpg',
        description: 'Màn hình LTPO AMOLED 6.7 inch, chip Tensor G2, camera chính 50MP'
    }
];

// Shopping cart
let cart = [];

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
}

// Display products
function displayProducts() {
    const productGrid = document.querySelector('.product-grid');
    
    if (!productGrid) return;
    
    productGrid.innerHTML = products.map(product => `
        <div class="product-card" data-id="${product.id}">
            <div class="product-image">
                <img src="${product.image}" alt="${product.name}">
            </div>
            <div class="product-info">
                <h3>${product.name}</h3>
                <p class="description">${product.description}</p>
                <div class="price">
                    ${formatCurrency(product.price)}
                    ${product.oldPrice ? `<span class="old-price">${formatCurrency(product.oldPrice)}</span>` : ''}
                </div>
                <button class="add-to-cart" data-id="${product.id}">
                    Thêm vào giỏ hàng
                </button>
            </div>
        </div>
    `).join('');
    
    // Add event listeners to all add to cart buttons
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', addToCart);
    });
}

// Add to cart function
function addToCart(e) {
    const productId = parseInt(e.target.dataset.id);
    const product = products.find(p => p.id === productId);
    
    if (!product) return;
    
    const existingItem = cart.find(item => item.id === productId);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            ...product,
            quantity: 1
        });
    }
    
    updateCartCount();
    showNotification(`Đã thêm ${product.name} vào giỏ hàng`);
}

// Update cart count in header
function updateCartCount() {
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
        cartCount.textContent = totalItems;
        cartCount.style.display = totalItems > 0 ? 'flex' : 'none';
    }
}

// Show notification
function showNotification(message) {
    // Create notification element if it doesn't exist
    let notification = document.querySelector('.notification');
    
    if (!notification) {
        notification = document.createElement('div');
        notification.className = 'notification';
        document.body.appendChild(notification);
        
        // Add styles for the notification
        const style = document.createElement('style');
        style.textContent = `
            .notification {
                position: fixed;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%) translateY(100px);
                background-color: #10b981;
                color: white;
                padding: 12px 24px;
                border-radius: 5px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                z-index: 1000;
                opacity: 0;
                transition: all 0.3s ease;
            }
            .notification.show {
                transform: translateX(-50%) translateY(0);
                opacity: 1;
            }
        `;
        document.head.appendChild(style);
    }
    
    // Set message and show notification
    notification.textContent = message;
    notification.classList.add('show');
    
    // Hide notification after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
    }, 3000);
}

// Mobile menu toggle
function initMobileMenu() {
    const menuToggle = document.createElement('div');
    menuToggle.className = 'menu-toggle';
    menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
    
    const header = document.querySelector('header .container');
    if (header) {
        header.appendChild(menuToggle);
        
        menuToggle.addEventListener('click', () => {
            document.querySelector('nav').classList.toggle('active');
        });
        
        // Close menu when clicking on a nav link
        document.querySelectorAll('nav a').forEach(link => {
            link.addEventListener('click', () => {
                document.querySelector('nav').classList.remove('active');
            });
        });
    }
}

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 80, // Adjust for fixed header
                behavior: 'smooth'
            });
        }
    });
});

// Form submission
function initContactForm() {
    const form = document.querySelector('.contact-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const formObject = {};
            formData.forEach((value, key) => {
                formObject[key] = value;
            });
            
            // Here you would typically send the form data to a server
            console.log('Form submitted:', formObject);
            
            // Show success message
            showNotification('Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.');
            
            // Reset form
            this.reset();
        });
    }
}

// Initialize everything when the DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    displayProducts();
    initMobileMenu();
    initContactForm();
    updateCartCount();
    
    // Add animation on scroll
    const animateOnScroll = () => {
        const elements = document.querySelectorAll('.product-card, .feature');
        
        elements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (elementTop < windowHeight - 50) {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }
        });
    };
    
    // Initial check
    animateOnScroll();
    
    // Check on scroll
    window.addEventListener('scroll', animateOnScroll);
});
