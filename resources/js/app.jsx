import '../sass/app.scss';
import './bootstrap';
import React from 'react';
import ReactDOM from 'react-dom/client';
import Index from './pages/Index';

if (document.getElementById('app')) {
    const root = ReactDOM.createRoot(document.getElementById('app'));
    root.render(<Index />);
}
