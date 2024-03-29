<section>
    <h1 class=" justify-center flex py-5 text-primary text-6xl font-bold">Thêm Sản Phẩm Mới</h1>
</section>

<section class="overflow-x-auto relative rounded-md flex justify-center py-9 ">
    <div class="max-w-screen-xl mx-auto px-4">
        <form class="flex flex-wrap " method="post" enctype="multipart/form-data">
            <div class="shadow-default rounded-lg p-4 w-full md:w-10/12">
                <div class="py-4 flex flex-wrap ">
                    <a class="font-medium text-blue-600 dark:text-blue-500 hover:underline" href="admin/products">Danh
                        sách sản phẩm</a> <br>
                    <?php if ($error_msg !== '') { ?>
                    <p class="text-red-500"><?php echo $error_msg ?></p>
                    <?php } ?>
                    <div class="w-full  md:pr-3 mt-4 md:mt-0">
                        <p class="text-sm mb-2">Tên Sản Phẩm </p>
                        <input name="name" required
                            class="appearance-none block w-full font-medium rounded-lg py-3 px-4 outline-[#808080] outline-1 outline-double focus:outline-primary focus:outline-2 duration-150 placeholder:text-[#808080]"
                            type="text">
                    </div>
                    <div class="w-full mt-4">
                        <p class="text-sm mb-2">Ảnh</p>
                        <input name="featured_image"
                            class="appearance-none block w-full font-medium rounded-lg py-3 px-4 outline-[#808080] outline-1 outline-double focus:outline-primary focus:outline-2 duration-150 placeholder:text-[#808080]"
                            type="file">
                    </div>
                    <div class="w-full mt-4">
                        <label for="large" class="block mb-2 text-base font-medium ">Màu Sắc</label>
                        <?php 
                            foreach (get_color() as $key => $value) {
                        ?>
                        <div
                            class="appearance-none block w-full font-medium rounded-lg py-3 px-4 outline-[#808080] outline-1 outline-double focus:outline-primary focus:outline-2 duration-150 placeholder:text-[#808080]">
                            <input class="form-check-input" type="checkbox" name="color[<?=$value['id']?>]"
                                value="<?php echo $value['id']?>">
                            <label class="form-check-label"
                                for="checkbox_auto_increment"><?php echo $value['name']?></label>
                        </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
                <div class="w-full md:pr-3 mt-4 md:mt-0 flex flex-wrap">
                    <label for="large" class="block mb-2 text-base font-medium ">Kích cỡ</label>
                    <?php 
                        foreach (get_size() as $key => $value) {
                    ?>
                    <div
                        class="appearance-none block w-full font-medium rounded-lg py-3 px-4 outline-[#808080] outline-1 outline-double focus:outline-primary focus:outline-2 duration-150 placeholder:text-[#808080]">
                        <input class="form-check-input" type="checkbox" name="size[<?=$value['id']?>]"
                            value="<?php echo $value['id']?>">
                        <label class="form-check-label"
                            for="checkbox_auto_increment"><?php echo $value['name']?></label>
                    </div>
                    <?php
                        }
                    ?>
                    <label for="large" class="block mb-2 text-base font-medium">Danh mục</label>
                    <select id="large" name="category"
                        class=" text-base text-white-900 border border-gray-300  dark:focus:ring-blue-500 dark:focus:border-blue-500 appearance-none block w-full font-medium rounded-lg py-3 px-4 outline-[#808080] outline-1 outline-double focus:outline-primary focus:outline-2 duration-150 placeholder:text-[#808080]">
                        <option value="0" selected>Chọn danh mục</option>
                        <?php 
                            foreach (get_category() as $key => $value) {
                        ?>
                        <option value="<?php echo $value['id']?>" id=""><?php echo $value['name']?></option>
                        <?php
                            } 
                        ?>
                    </select>
                    <div class="w-full md:pr-3 mt-5 md:mt-0">
                        <p class="text-sm mb-2 mt-5">Mô tả ngắn</p>
                        <textarea name="description" required
                            class="resize-none  appearance-none block w-full font-medium rounded-lg py-3 px-4 outline-[#808080] outline-1 outline-double focus:outline-primary focus:outline-2 duration-150 placeholder:text-[#808080]"></textarea>
                    </div>
                    <div class="w-full md:pr-3 mt-5 md:mt-0">
                        <p class="text-sm mb-2 mt-5">Mô tả sản phẩm</p>
                        <textarea name="description_detail" required
                            class="resize-none  appearance-none block w-full font-medium rounded-lg py-3 px-4 outline-[#808080] outline-1 outline-double focus:outline-primary focus:outline-2 duration-150 placeholder:text-[#808080]"></textarea>
                    </div>
                    <div class="w-full  md:pr-3 mt-4 md:mt-0">
                        <p class="text-sm mb-2 mt-5">Giá gốc</p>
                        <input name="price" required
                            class="appearance-none block w-full font-medium rounded-lg py-3 px-4 outline-[#808080] outline-1 outline-double focus:outline-primary focus:outline-2 duration-150 placeholder:text-[#808080]"
                            type="text" placeholder="">
                    </div>
                    <div class="w-full  md:pr-3 mt-4 md:mt-0">
                        <p class="text-sm mb-2 mt-5">Giá sale</p>
                        <input name="discount" required
                            class="appearance-none block w-full font-medium rounded-lg py-3 px-4 outline-[#808080] outline-1 outline-double focus:outline-primary focus:outline-2 duration-150 placeholder:text-[#808080]"
                            type="text" placeholder="">
                    </div>
                </div>
                <button type="submit"
                    class="appearance-none block mt-5 font-medium rounded-lg py-3 px-4 outline-[#808080] outline-1 outline-double focus:outline-primary focus:outline-2 duration-150 placeholder:text-[#808080]">
                    Thêm Sản Phẩm
                </button>
            </div>
        </form>
    </div>
</section>