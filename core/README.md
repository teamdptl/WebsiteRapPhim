Hướng dẫn sử dụng các phương thức bên trong lớp Model:
- phương thức tìm kiếm theo id cụ thể: find($option, ...$ids):bool
    Hàm này trả về đối tượng hay danh sách đối tượng được tìm thấy dựa vào các id mà người dùng cung cấp, có thể thực hiện đối với các đối tượng có nhiều khóa chính (primary key)
        + $option: cách thức tìm kiếm đối với các đối tượng có thuộc tính isDeleted
            Model::UN_DELETED_OBJ sẽ là tìm kiếm đối tượng mà có thuộc tính isDeleted = false
            Model::ALL_OBJ sẽ tìm kiếm đối tượng k phân biệt giá trị của thuộc tính isDeleted
            Model::ONLY_DELETED_OBJ sẽ tìm kiếm đối tượng mà có isDeleted = true
        + ...$ids: các đối số là giá trị của khóa chính (primary key) hay còn gọi là 'id' mà người dùng truyền vào (điều kiện: số lượng đối số < số lượng khóa chính (primary key) của đối tượng)
    Ví dụ: Movie::find(Model::UN_DELETED_OBJ, 'mID1');

- phương thức tìm kiếm tất cả: findAll($option): array|bool
    Tương tự như hàm find ở trên, nhưng hàm nãy không tìm kiếm dựa vào id mà sẽ lấy tất cả các đối tượng của bảng
    + $option: tương tự như trên
    Ví dụ: Movie::findAll(Model::ONLY_DELETED_OBJ);

- phương thức thêm đối tượng: save($object):int
    + $object: đối tượng cần thêm vào database
    Ví dụ: Movie::save(đối tượng cần thêm vào);

- phương thức cập nhật đối tượng: update($object, ...$ids): bool
    + $object: đối tượng lưu thông tin cần thay đổi
    + ...$ids: các đối số là giá trị của khóa chính (primary key) hay còn gọi là 'id' mà người dùng truyền vào (điều kiện: số lượng đối số < số lượng khóa chính (primary key) của đối tượng)
    Ví dụ: FeaturePermission::update($object, 'permissionID1', 'featureID1', 'actionID1');

- phương thức xóa đối tượng: delete($softDelete, ...$ids): bool
    Phương thức này cho phép người dùng thực hiện việc xóa đối tượng theo 2 cách hoặc là delete khỏi database hoặc là set thuộc tính isDeleted = true
        + $softDelete: biến để ng dùng quyết định xóa theo cách nào, nếu ng dùng truyền giá trị true cho đối số này thì hàm sẽ chỉ set thuộc tính isDeleted = true và ngược lại. Đặc biệt với những đối tượng cần xóa mà không có thuộc tính isDeleted thì mặc định sẽ xóa đối tượng khỏi database
        + ...$ids: tương tự như trên
    Ví dụ: FeaturePermission::delete(true, 'permissionID1', 'featureID1', 'actionID');
