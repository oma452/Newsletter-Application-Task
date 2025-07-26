import { useForm } from '@inertiajs/react';

export default function Subscribe() {
  const { data, setData, post, processing, errors, reset } = useForm({
    email: '',
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    post('/subscription/toggle', {
      onSuccess: () => {
        alert('Subscription preference updated successfully!');
        reset();
      },
    });
  };

  return (
    <div className="p-6 max-w-md mx-auto bg-white rounded shadow">
      <h2 className="text-xl font-semibold mb-4">Subscribe to Newsletter</h2>
      <p className="mb-4">To subscribe to our newsletter, please log in to your account.</p>
      <p className="mb-4">Once logged in, you can toggle your subscription status from your profile.</p>
      <div className="mt-4">
        <a href="/login" className="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
          Log In
        </a>
        <a href="/register" className="ml-2 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
          Register
        </a>
      </div>
    </div>
  );
}
