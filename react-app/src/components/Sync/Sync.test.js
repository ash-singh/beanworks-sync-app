import React from 'react';
import ReactDOM from 'react-dom';
import Sync from './Sync';


it('Sync renders without crashing', () => {
    const div = document.createElement('div');
    ReactDOM.render(<Sync />, div);
});