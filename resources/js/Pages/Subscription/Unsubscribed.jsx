import React from 'react';
import { Head } from '@inertiajs/react';

export default function Unsubscribed({ email }) {
  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-100">
      <Head title="Unsubscribed" />
      
      <div className="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
        <div className="text-center mb-6">
          <svg className="mx-auto h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
          </svg>
          <h2 className="mt-3 text-lg font-medium text-gray-900">Unsubscribed Successfully</h2>
          <p className="mt-1 text-sm text-gray-500">
            {email} has been unsubscribed from our newsletter.
          </p>
        </div>
        
        <p className="text-sm text-gray-500 text-center">
          If you change your mind, you can always subscribe again from your profile.
        </p>
      </div>
    </div>
  );
}