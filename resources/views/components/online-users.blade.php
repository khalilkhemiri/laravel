<div>
    <h3>Online Users</h3>
    <ul id="online-users-list">
        @foreach($users as $user)
            <li data-user-id="{{ $user->id }}">
                {{ $user->name }}
                <span class="user-status text-success">(Online)</span>
            </li>
        @endforeach
    </ul>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const updateUserStatus = (userId, isOnline) => {
        const userElement = document.querySelector(`li[data-user-id="${userId}"]`);
        if (userElement) {
            const statusSpan = userElement.querySelector('.user-status');
            if (statusSpan) {
                statusSpan.textContent = isOnline ? '(Online)' : '(Offline)';
                statusSpan.className = `user-status ${isOnline ? 'text-success' : 'text-danger'}`;
            }
            if (!isOnline) {
                userElement.remove();
            }
        } else if (isOnline) {
            // If the user is not in the list and is online, add them
            const list = document.getElementById('online-users-list');
            const li = document.createElement('li');
            li.setAttribute('data-user-id', userId);
            li.innerHTML = `${userId} <span class="user-status text-success">(Online)</span>`;
            list.appendChild(li);
        }
    };

    window.Echo.join('presence-online-users')
        .here((users) => {
            console.log('Current users:', users);
            users.forEach((user) => {
                updateUserStatus(user.id, true);
            });
        })
        .joining((user) => {
            console.log('User joined:', user);
            updateUserStatus(user.id, true);
        })
        .leaving((user) => {
            console.log('User left:', user);
            updateUserStatus(user.id, false);
        })
        .listen('UserActivity', (e) => {
            console.log('User activity:', e);
            updateUserStatus(e.user.id, true);
        })
        .listen('UserOffline', (e) => {
            console.log('User offline:', e);
            updateUserStatus(e.user.id, false);
        })
        .error((error) => {
            console.error('Error joining the presence channel:', error);
        });
});
</script>